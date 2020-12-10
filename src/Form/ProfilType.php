<?php

namespace App\Form;

use App\Entity\Profil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Pseudo'
            ])
            ->add('picture', FileType::class, [
                'mapped' => false,
                'label' => 'Avatar'
            ])
            ->add('control', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Contrôle parental'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profil::class,
        ]);
    }
}
