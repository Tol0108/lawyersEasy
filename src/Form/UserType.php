<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Specialite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('login', TextType::class, [
                'label' => 'Login'
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'required' => false
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'required' => false
            ])
            ->add('licenceNumber', TextType::class, [
                'label' => 'Numéro de licence',
                'required' => false
            ])
            ->add('isVerified', CheckboxType::class, [
                'label' => 'Vérifié ?',
                'required' => false
            ])
            
            /*
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Client' => 'ROLE_CLIENT',
                    'Avocat' => 'ROLE_AVOCAT'
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Rôles'
            ])

            */
            ->add('specialite', EntityType::class, [
                'class' => Specialite::class,
                'choice_label' => 'nom',
                'label' => 'Spécialité'
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => false,
                'attr' => ['placeholder' => 'Laisser vide si inchangé']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
