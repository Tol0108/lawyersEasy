<?php

namespace App\Form;

use App\Entity\Avocat;
use App\Entity\Disponibilite;
use App\Entity\Reservations;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_reservation', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('disponibilite', EntityType::class, [
                'class' => Disponibilite::class,
                'choice_label' => function($disponibilite) {
                    return $disponibilite->getDate()->format('d/m/Y H:i');
                },
                'label' => 'Choisir un créneau disponible',
                'expanded' => false,
                'multiple' => false
            ])
            ->add('documents', FileType::class, [
                'label' => 'Télécharger des documents',
                'required' => false,
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservations::class,
        ]);
    }
}
