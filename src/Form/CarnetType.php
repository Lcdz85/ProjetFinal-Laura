<?php

namespace App\Form;

use App\Entity\Carnet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Length;

class CarnetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Le titre ne peut pas dépasser 50 caractères.',
                    ]),
                ],
                'attr' => [
                    'maxlength' => 50,
                ],
            ])
            ->add('photo', FileType::class, [
                'label' => "Sélectionner une photo de couverture",
                'mapped' => false, 
                    // cette propriété ne sera pas affecté dans l'entité quand on envoie le formulaire. On la récuperera avec $form['photo']->getData()
                'required' => false 
                    // l'utilisateur n'est pas obligé d'uploader un fichier
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carnet::class,
        ]);
    }
}