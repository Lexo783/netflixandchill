<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, [
                'label' => "Quel nom voulez mettre à votre adresse ?",
                'attr' => [
                    'placeholder' => "Nommez votre adresse"
                ]
            ])
            ->add('firstName',TextType::class, [
                'label' => "Votre prénom",
                'attr' => [
                    'placeholder' => "Entrez votre prénom"
                ]
            ])
            ->add('lastName',TextType::class, [
                'label' => "Votre nom",
                'attr' => [
                    'placeholder' => "Entrez votre nom"
                ]
            ])
            ->add('company',TextType::class, [
                'label' => "Votre société",
                'required' => false,
                'attr' => [
                    'placeholder' => "falcultatif"
                ]
            ])
            ->add('address',TextType::class, [
                'label' => "Votre adresses",
                'attr' => [
                    'placeholder' => "8 rue des villas"
                ]
            ])
            ->add('postal',TextType::class, [
                'label' => "Votre code postale",
                'attr' => [
                    'placeholder' => "Entrez votre code postale"
                ]
            ])
            ->add('city',TextType::class, [
                'label' => "Votre ville",
                'attr' => [
                    'placeholder' => "Entrez votre ville"
                ]
            ])
            ->add('country',CountryType::class, [
                'label' => "Pays",
                'attr' => [
                    'placeholder' => "8 rue des villas"
                ]
            ])
            ->add('phone',TelType::class, [
                'label' => "Votre Telephone",
                'attr' => [
                    'placeholder' => "Votre téléphone"
                ]
            ])
            ->add('submite', SubmitType::class,[
                'label' => "Ajouter mon addresse"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
