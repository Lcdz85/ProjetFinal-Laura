<?php

namespace App\Form;

use App\Entity\Carnet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CarnetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un titre pour votre carnet.'
                    ]),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Le titre ne peut pas dépasser 50 caractères.',
                    ]),
                ],
                'attr' => [
                    'maxlength' => 50,
                    'class' => 'form-control',
                    'placeholder' => 'Titre du carnet'
                ],
                'label' => 'Titre du carnet',
                'required' => true
            ])
            ->add('photo', FileType::class, [
                'label' => "Sélectionner une photo de couverture",
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner une photo de couverture pour votre carnet.'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carnet::class,
        ]);
    }
}