<?php

namespace App\Controller;

use App\Entity\GuideJuridique;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GuideJuridiqueController extends AbstractController
{
        #[Route('/articles', name: 'articles')]
    public function articles(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(GuideJuridique::class)->findAll();

        return $this->render('guideJuridique/articles.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}', name: 'article_detail')]
    public function articleDetail(GuideJuridique $article): Response
    {
        return $this->render('guideJuridique/article_detail.html.twig', [
            'article' => $article,
        ]);
    }



    #[Route('/guidejuridique/new', name: 'guidejuridique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $guideJuridique = new GuideJuridique();
        $form = $this->createForm(GuideJuridiqueType::class, $guideJuridique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($guideJuridique);
            $entityManager->flush();

            return $this->redirectToRoute('guidejuridique');
        }

        return $this->render('guidejuridique/new.html.twig', [
            'guideJuridiqueForm' => $form->createView(),
        ]);
    }

    #[Route('/guidejuridique/{id}/edit', name: 'guidejuridique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GuideJuridique $guideJuridique, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GuideJuridiqueType::class, $guideJuridique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('guidejuridique');
        }

        return $this->render('guidejuridique/edit.html.twig', [
            'guideJuridique' => $guideJuridique,
            'guideJuridiqueForm' => $form->createView(),
        ]);
    }

    #[Route('/guidejuridique/{id}', name: 'guidejuridique_delete', methods: ['POST'])]
    public function delete(Request $request, GuideJuridique $guideJuridique, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$guideJuridique->getId(), $request->request->get('_token'))) {
            $entityManager->remove($guideJuridique);
            $entityManager->flush();
        }

        return $this->redirectToRoute('guidejuridique');
    }
}

