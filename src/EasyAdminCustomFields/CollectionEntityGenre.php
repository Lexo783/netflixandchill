<?php


namespace App\EasyAdminCustomFields;


use App\Form\CollectionGenreType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

final class CollectionEntityGenre implements FieldInterface
{
    use FieldTrait;
    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            // you can use your own form types too
            ->setFormType(CollectionGenreType::class)
            ;
    }
}
