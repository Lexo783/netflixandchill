<?php

namespace App\Controller\Admin;

use App\EasyAdminCustomFields\CollectionEntityGenre;
use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\ORM\Cache\AssociationCacheEntry;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
<<<<<<< Updated upstream
=======
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
>>>>>>> Stashed changes

class MovieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Movie::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
<<<<<<< Updated upstream
            TextEditorField::new('description'),
=======
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
>>>>>>> Stashed changes
        ];
    }
    */
}
