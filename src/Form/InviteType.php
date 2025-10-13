<?php

namespace App\Form;

use App\Entity\Invitation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Carnet;

class InviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('carnet', EntityType::class, [
                'class' => Carnet::class,
                'choices' => $options['carnets'],
                'choice_label' => 'titre',
                'label' => 'Choisir un carnet',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email de l\'invité',
            ]);
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invitation::class,
            'carnets' => [],
        ]);
    }
}
