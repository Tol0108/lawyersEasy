<?php

namespace App\Form;

use App\Entity\Avocat;
use APP\Entity\Users;
use App\Entity\Reservations;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date_reservation', DateTimeType::class, [
          //  'widget' => 'single_text', // Utiliser un widget de calendrier
            // 'format' => 'yyyy-MM-dd HH:mm', // Format à ajuster
            ])
            ->add('avocat', EntityType::class, [
                'class' => Avocat::class,
                'choice_label' => 'nom',
            
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'en attente',
                    'Confirmé' => 'confirmé',
                    'Annulé' => 'annulé',
                ],
                'required' => true,
            ])
            
            // Ajout du champ 'user'
            ->add('user', EntityType::class, [
                'class' => Users::class,
                'choice_label' => function (Users $user) {
                    return $user->getEmail(); 
                },
                'placeholder' => 'Choisir un utilisateur',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservations::class,
        ]);
    }
}
