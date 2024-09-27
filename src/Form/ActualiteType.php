<?php

namespace App\Form;

use App\Entity\Avocat;
use App\Entity\Specialite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvocatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('codePostal')
            ->add('telephone')
            ->add('photo') // Si tu gères l'upload de photo
            ->add('specialite', EntityType::class, [
                'class' => Specialite::class,
                'choice_label' => 'nom', // Affiche le nom des spécialités
                'label' => 'Spécialité',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avocat::class,
        ]);
    }
}
