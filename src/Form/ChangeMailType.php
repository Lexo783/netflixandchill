<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeMailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('newEmail',TextType::class, [
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => "Nouvel Email",
                    'class' => "textbox"
                ]
            ])
            ->add('submite', SubmitType::class,[
                'label' => "Changer mon Email",
                'attr' => [
                    'class' => "btn_register"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
