<?php

namespace App\Form;

use App\Entity\Avocat;
use App\Entity\Specialite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvocatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'required' => false,
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
                'required' => false,
            ])
            ->add('specialite', EntityType::class, [
                'class' => Specialite::class,
                'choice_label' => 'nom',
                'label' => 'Spécialité'
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo de l\'avocat',
                'mapped' => false,
                'required' => false,
                'attr' => ['accept' => 'image/jpeg, image/png']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avocat::class,
        ]);
    }
}
