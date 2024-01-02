<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Entity\Reservations; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;


class DocumentController extends AbstractController
{

    #[Route('/new/{id}', name: 'document_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Reservations $reservation, EntityManagerInterface $entityManager): Response
    {
        $document = new Document(); // Instanciez un nouveau Document
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            if ($file) {
                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('documents_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    // Gérer l'exception si un problème survient lors du téléchargement du fichier
                    $this->addFlash('error', 'Un problème est survenu lors du téléchargement de votre fichier');
                    return $this->redirectToRoute('document_new', ['id' => $reservation->getId()]);
                }

                $document->setChemin($fileName);
                $document->setReservation($reservation); // Lier le document à la réservation

                $entityManager->persist($document);
                $entityManager->flush();

                // Redirigez vers une route qui affiche le document ou une confirmation
                return $this->redirectToRoute('document_show', ['id' => $document->getId()]);
            }
        }

        // Rendre la vue du formulaire si non soumis ou non valide
        return $this->render('document/new.html.twig', [
            'documentForm' => $form->createView(),
        ]);
    }

    private function generateUniqueFileName()
    {
        // md5() réduit la similarité des noms de fichiers générés par
        // uniqid(), qui est basé sur des timestamps
        return md5(uniqid());
    }

}