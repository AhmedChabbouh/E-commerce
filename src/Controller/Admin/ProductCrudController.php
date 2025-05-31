<?php

namespace App\Controller\Admin;

use App\Entity\Notification;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\DomCrawler\Field\FileFormField;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class ProductCrudController extends AbstractCrudController
{
    private HubInterface $hub;
    private MailerInterface $mailer;
    public function __construct(HubInterface $hub,MailerInterface $mailer)
    {
        $this->hub = $hub;
        $this->mailer = $mailer;
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
            MoneyField::new('price')->setCurrency('USD'),
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
            $message= $entityInstance->getName() . ' is now ' . $newSale . '% off';
            $users= $entityManager->getRepository(User::class)->findbyWishlistedProduct($entityInstance->getId());
            foreach ($users as $user){
                $notification = new Notification();
                $notification->setTitle($title);
                $notification->setMessage($message);
                $notification->setOwner($user);
                $entityManager->persist($notification);
                $this->mailer->send((new Email())
                    ->from(new Address('symfonyecommerce34@gmail.com', 'E-Commerce No-Reply'))
                    ->to((string) $user->getEmail())
                    ->subject($title)
                    ->html("<h1>".$title."</h1><p>".$message."</p>")

                );
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
    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)->add(Crud::PAGE_INDEX,Action::DETAIL);
    }
}
