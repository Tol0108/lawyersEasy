<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Avocat;
use App\Form\AvocatType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class AvocatController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private string $photosDirectory;

    public function __construct(EntityManagerInterface $entityManager, string $photosDirectory)
    {
        $this->entityManager = $entityManager;
        $this->photosDirectory = $photosDirectory;
    }

    #[Route('/avocat', name: 'avocat_list')]
    public function index(): Response
    {
        $avocats = $this->entityManager->getRepository(Avocat::class)->findAll();
        return $this->render('avocat/index.html.twig', [
            'avocats' => $avocats,
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]

    #[Route('/avocat/new', name: 'avocat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        /** @var Users $user */
        $user = $this->getUser();

        if (!$user instanceof Users) {
            throw new \LogicException('L\'utilisateur n\'est pas une instance de Users.');
        }

        // Vérifier si l'utilisateur a déjà un profil avocat
        if ($user->getAvocat()) {
            $this->addFlash('warning', 'Vous avez déjà créé votre profil avocat.');
            return $this->redirectToRoute('avocat_list');
        }

        $avocat = new Avocat();
        $avocat->setUser($user);

        $form = $this->createForm(AvocatType::class, $avocat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de la photo
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->photosDirectory,
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors du téléchargement de l\'image.');
                    // Vous pouvez gérer l'exception ou rediriger
                }

                $avocat->setPhoto($newFilename);
            }

            // Attribuer le rôle ROLE_AVOCAT à l'utilisateur
            $roles = $user->getRoles();
            if (!in_array('ROLE_AVOCAT', $roles)) {
                $roles[] = 'ROLE_AVOCAT';
                $user->setRoles($roles);
                $this->entityManager->persist($user);
            }

            $this->entityManager->persist($avocat);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre profil avocat a été créé avec succès.');
            return $this->redirectToRoute('avocat_list');
        }

        return $this->render('avocat/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
