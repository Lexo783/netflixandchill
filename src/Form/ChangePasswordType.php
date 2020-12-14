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
                'label' => 'Mot de passe actuel',
                'mapped' => false,
                'attr' => [
                    'placehoder' => 'votre mot de passe actuelle'
                ]
            ])
            ->add('newPassword',PasswordType::class, [
                'label' => "Nouveau mot de passe",
                'mapped' => false,
                'attr' => [
                    'placeholder' => "Nouveau mot de passe"
                ]
            ])
            ->add('newPasswordConfirm',PasswordType::class, [
                'label' => "Confirmer le mot de passe",
                'mapped' => false,
                'attr' => [
                    'placeholder' => "Confirmer le nouveau mot de passe"
                ]
            ])
            ->add('submite', SubmitType::class,[
                'label' => "Changer mon mot de passe"
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
