<?php

namespace App\Form;

use App\Entity\Carnet;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class AccesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
        ->add('carnet', EntityType::class, [
            'class' => Carnet::class,
            'choices' => $options['carnets'], 
            'choice_label' => 'titre',
            'label' => 'Carnet à partager',
        ])
        ->add('user', EntityType::class, [
            'class' => Utilisateur::class,
            'choice_label' => 'username',
            'label' => 'Utilisateur à ajouter',
            'query_builder' => function (EntityRepository $er) use ($user) {
                return $er->createQueryBuilder('u')
                    ->where('u != :user')
                    ->setParameter('user', $user)
                    ->orderBy('u.username', 'ASC');
            },
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'user' => null,
            'carnets' => [],
        ]);
    }
}
