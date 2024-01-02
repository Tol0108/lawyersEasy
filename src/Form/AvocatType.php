<?php

namespace App\Form;

use App\Entity\Avocat;
use App\Entity\Specialite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AvocatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class) // Champ pour le nom de l'avocat
            ->add('specialite', EntityType::class, [
                'class' => Specialite::class,
                'choice_label' => 'nom', // Le nom de la spécialité juridique
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo de l\'avocat',
                'mapped' => false, // si le champ n'est pas directement lié à la propriété de l'entité
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avocat::class,
        ]);
    }
}

