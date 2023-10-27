<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Enum\BookStatus;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Symfony\Component\HttpKernel\KernelInterface;

class BookCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Book::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('isbn'),
            IntegerField::new('pageCount'),
            DateField::new('publishedDate'),
            TextareaField::new('longDescription'),
            ChoiceField::new('status')->setChoices(BookStatus::cases()),
            AssociationField::new('category')->autocomplete(),
            ImageField::new('image')
                ->setBasePath('uploads/books')
                ->setUploadDir('/public/uploads/books')
                ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]')
        ];
    }
}
