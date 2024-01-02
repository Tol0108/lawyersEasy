<?php

namespace App\Form;

use App\Entity\Disponibilite;
use App\Entity\Avocat;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisponibiliteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDateTime', DateTimeType::class, [
                'widget' => 'single_text', // Pour utiliser un widget de sélection de date et d'heure HTML5
                // Configurez ceci selon les besoins de votre application
            ])
            ->add('avocat', EntityType::class, [
                'class' => Avocat::class,
                'choice_label' => 'nom', // ou 'fullName' si vous avez un attribut qui combine le nom et le prénom
                // Configurez ceci selon les besoins de votre application
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Disponibilite::class,
        ]);
    }
}
