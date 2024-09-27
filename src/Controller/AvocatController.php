<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Avocat;
use App\Entity\Disponibilite;
use App\Form\AvocatType;
use App\Form\DisponibiliteType;
use App\Repository\AvocatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AvocatController extends AbstractController
{
    private $entityManager;
    private $photosDirectory;

    public function __construct(EntityManagerInterface $entityManager, string $photosDirectory)
    {
        $this->entityManager = $entityManager;
        $this->photosDirectory = $photosDirectory;
    }

    #[Route('/avocats', name: 'avocat_list')]
    public function list(): Response
    {
        $avocats = $this->entityManager->getRepository(Avocat::class)->findAll();

        return $this->render('user/list.html.twig', [
            'avocats' => $avocats,
        ]);
    }

    #[Route('/avocat/disponibilites', name: 'avocat_disponibilites', methods: ['GET', 'POST'])]
    public function disponibilites(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $disponibilite = new Disponibilite();
        $form = $this->createForm(DisponibiliteType::class, $disponibilite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $disponibilite->setAvocat($user);
            $entityManager->persist($disponibilite);
            $entityManager->flush();

            $this->addFlash('success', 'Disponibilités ajoutées avec succès.');
            return $this->redirectToRoute('disponibilite_list');
        }

        return $this->render('disponibilites/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/avocat/new', name: 'avocat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $avocat = new Avocat();
        $form = $this->createForm(AvocatType::class, $avocat);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photoFile */
            $photoFile = $form->get('photo')->getData();
    
            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension();

            // Déplacer le fichier dans le répertoire de destination
            $photoFile->move(
                $this->getParameter('photos_directory'),
                $newFilename
            );
    
                $avocat->setPhoto($newFilename);
            }
    
            $entityManager->persist($avocat);
            $entityManager->flush();
    
            return $this->redirectToRoute('avocat_list');
        }
    
        return $this->render('admin/avocat_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Fonction pour envoyer un email de confirmation
    public function envoyerConfirmation(MailerInterface $mailer, $clientEmail, $avocatEmail)
    {
        $email = (new Email())
            ->from($avocatEmail)
            ->to($clientEmail)
            ->subject('Confirmation de rendez-vous')
            ->html('<p>Votre rendez-vous a été confirmé. Veuillez trouver les documents attachés.</p>');

        $mailer->send($email);
    }
}
