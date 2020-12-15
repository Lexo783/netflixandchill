<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class MovieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Movie::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            UrlField::new('movie'),
            TextField::new('title'),
            TextEditorField::new('plot'),
            ChoiceField::new('rated')
                ->setChoices([
                    'PEGI-3' => 'PEGI-3',
                    'PEGI-7' => 'PEGI-7',
                    'PEGI-12' => 'PEGI-12',
                    'PEGI-16' => 'PEGI-16',
                    'PEGI-18' => 'PEGI-18',
                ]),
            DateField::new('released'),
            AssociationField::new('genres'),
            ImageField::new('picture')
                ->setBasePath('assets/movies')
                ->setUploadDir('public/assets/movies')
                ->setRequired(false),
            TextField::new('type'),
        ];
    }
}
