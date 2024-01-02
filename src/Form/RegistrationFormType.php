<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Role;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class)
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('email', EmailType::class)
            ->add('Password', PasswordType::class, [
                'mapped' => false,
                'attr' =>  ['autocomplete' => 'new-password', 'class' => 'form-control'],  
            ])
            ->add('telephone', TelType::class, [
                'required' => false,
            ])
            ->add('langue', TextType::class, [
                'required' => false,
            ])
            ->add('status', ChoiceType::class,[
                'choices' => [
                    'Actif' => 'actif',
                    'Inactif' => 'inactif',
                ],
            ])
            
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Client' => 'client',
                    'Avocat' => 'avocat',
                ],
                'label' => 'Je suis :',
            ])
            
            ->add('isActive', CheckboxType::class, [
                'required' => false,
                'label'    => 'Actif',
                // 'data' => true,
            ])
            ->add('role', EntityType::class, [
                'class' => Role::class,
                'choice_label' => 'name',
                'label' => 'Rôle :',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
