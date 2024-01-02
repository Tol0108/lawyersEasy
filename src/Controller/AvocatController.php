<?php

namespace App\Controller;

use App\Entity\Avocat;
use App\Form\AvocatType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException; 

class AvocatController extends AbstractController
{
    #[Route('/avocat/new', name: 'avocat_new')]
    #[IsGranted("ROLE_ADMIN")]
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $avocat = new Avocat();
        $form = $this->createForm(AvocatType::class, $avocat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload de la photo
        $file = $form->get('photo')->getData(); // Assurez-vous que 'photo' est le nom du champ dans votre formulaire
        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            // Déplacer le fichier dans le répertoire où les photos sont stockées
            try {
                $file->move(
                    $this->getParameter('photos_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Gérer l'exception si quelque chose se passe mal pendant l'upload du fichier
                $this->addFlash('error', 'Problème lors de l\'upload de la photo.');
            }

            // Mise à jour de la propriété 'photo' de l'entité 'Avocat' pour stocker le nom du fichier
            $avocat->setPhoto($newFilename);
        }
            $entityManager->persist($avocat);
            $entityManager->flush();

            $this->addFlash('success', 'L\'avocat a été créé avec succès.');
            return $this->redirectToRoute('list');
        }

        return $this->render('avocat/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/avocat/list', name: 'list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $avocats = $entityManager->getRepository(Avocat::class)->findAll();

        return $this->render('avocat/list.html.twig', [
            'avocats' => $avocats,
        
        ]);
    }
}
