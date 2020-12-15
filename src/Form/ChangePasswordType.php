<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword',PasswordType::class,[
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'votre mot de passe actuelle',
                    'class' => 'textbox'
                ]
            ])
            ->add('newPassword',PasswordType::class, [
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => "Nouveau mot de passe",
                    'class' => 'textbox'
                ]
            ])
            ->add('newPasswordConfirm',PasswordType::class, [
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => "Confirmer le nouveau mot de passe",
                    'class' => 'textbox'
                ]
            ])
            ->add('submite', SubmitType::class,[
                'label' => "Changer mon mot de passe",
                'attr' => [
                    'class' => 'btn_register'
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
