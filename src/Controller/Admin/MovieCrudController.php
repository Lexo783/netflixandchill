<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MovieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Movie::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextEditorField::new('plot'),
            ChoiceField::new('rated')
                ->setChoices([
                    'PEGI-3' => 3,
                    'PEGI-7' => 7,
                    'PEGI-12' => 12,
                    'PEGI-16' => 16,
                    'PEGI-18' => 18,
                ]),
            DateField::new('released'),
            AssociationField::new('genres'),
            //CollectionEntityGenre::new('genres'),
            //ImageField::new('picture'),
            TextField::new('type'),
        ];
    }
}
