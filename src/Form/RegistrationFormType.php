<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Specialite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Champs de base
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('email', EmailType::class)
            ->add('adresse', TextType::class, [
                'required' => true,
                'label' => 'Adresse',
            ])
            ->add('telephone', TelType::class, [
                'required' => false,
            ])
            ->add('codePostal', TextType::class, [
                    'label' => 'Code postal',
                    'required' => true,
            ])

            // Choix des rôles, mappé pour être sauvegardé dans l'entité
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Client' => 'ROLE_USER',
                    'Avocat' => 'ROLE_AVOCAT',
                ],
                'expanded' => true,
                'multiple' => false,
                'mapped' => false,
                'label' => 'Je suis :'
            ])

            // Mot de passe, non mappé (géré séparément)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répétez le mot de passe'],
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ]);

        // Event listeners pour la gestion des rôles spécifiques
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            // Détection du rôle sélectionné
            $role = $data && $data->getRoles() ? $data->getRoles()[0] : null;
            $this->setupRoleSpecificFields($form, $role);
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            $role = $data['roles'] ?? null;

            $this->setupRoleSpecificFields($form, $role);
        });
    }

    // Gestion des champs spécifiques au rôle d'Avocat
    private function setupRoleSpecificFields($form, $role)
    {
        if ($role === 'ROLE_AVOCAT') {
            $form->add('licenceNumber', TextType::class, ['required' => true]);
            $form->add('specialite', EntityType::class, [
                'class' => Specialite::class,
                'choice_label' => 'nom',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
