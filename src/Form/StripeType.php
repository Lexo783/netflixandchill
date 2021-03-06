<?php


namespace App\Form;


use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class StripeType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Pseudo",
                    'class' => 'textbox'
                ]
            ])
            ->add('picture', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Merci de renseigner uniquement une image de type jpeg ou png de maximum 1mo',
                    ])
                ],
            ])
            ->add('control', CheckboxType::class, [
                'mapped' => false,
                'label' => false,
                'required' => false
            ])
        ;
    }
}