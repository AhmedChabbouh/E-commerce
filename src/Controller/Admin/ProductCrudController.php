<?php

namespace App\Controller\Admin;

use App\Entity\Notification;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\DomCrawler\Field\FileFormField;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class ProductCrudController extends AbstractCrudController
{
    private HubInterface $hub;
    public function __construct(HubInterface $hub)
    {
        $this->hub = $hub;
    }

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $imageField = ImageField::new('image')->setBasePath('/uploads/images')->setUploadDir('public/uploads/images')
            ->setUploadedFileNamePattern(fn(UploadedFile $file) => 'product' . uniqid() . '.' . $file->guessExtension());
        if ($pageName != 'new'){
            $imageField->setRequired(false);
        }
        return [

            TextField::new('name'),
            TextEditorField::new('description'),
            TextField::new('price'),
            NumberField::new('sale'),
            $imageField,
                //TODO: change the filename format
            IntegerField::new('stockQuantity'),

        ];
    }
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {

        $uow= $entityManager->getUnitOfWork();
        $originalData = $uow->getOriginalEntityData($entityInstance);

        parent::updateEntity($entityManager, $entityInstance);

        if (!$entityInstance instanceof Product) {
            return;
        }
        $newSale= $entityInstance->getSale();
        $oldSale= $originalData['sale'];
        if ($newSale > $oldSale){
            $title="New Sale!";
            $message= $entityInstance->getName() . 'is now ' . $newSale . '% off';
            $users= $entityManager->getRepository(User::class)->findbyWishlistedProduct($entityInstance->getId());
            foreach ($users as $user){
                $notification = new Notification();
                $notification->setTitle($title);
                $notification->setMessage($message);
                $notification->setOwner($user);
                $entityManager->persist($notification);
            }
            $entityManager->flush();
            $update = new Update(
                "http://localhost:8000/product/" . $entityInstance->getId(),
                json_encode([
                    'title' => $title,
                    'message' => $message,
                ])
            );
            $this->hub->publish($update);

        }


    }
}
