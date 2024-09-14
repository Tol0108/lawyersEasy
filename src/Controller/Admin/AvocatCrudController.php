<?php

namespace App\Controller\Admin;

use App\Entity\Avocat;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AvocatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Avocat::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('adresse'),
            TextField::new('telephone'),
            AssociationField::new('specialite'),
            TextField::new('photo'),
            // Champs de l'entité Users associée
            AssociationField::new('user')
                ->setFormTypeOption('choice_label', function ($user) {
                    return $user->getEmail() . ' (' . $user->getNom() . ' ' . $user->getPrenom() . ')';
                })
                ->autocomplete(),
        ];
    }
    
}
