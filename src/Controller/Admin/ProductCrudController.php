<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\DomCrawler\Field\FileFormField;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductCrudController extends AbstractCrudController
{
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
            $imageField,
                //TODO: change the filename format
            IntegerField::new('stockQuantity'),

        ];
    }
}
