<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePseudoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('newPseudo',TextType::class, [
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => "Votre nouveau pseudo",
                    'class' => "textbox"
                ]
            ])
            ->add('submite', SubmitType::class,[
                'label' => "Changer mon pseudo",
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
