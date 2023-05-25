<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Mot;
use App\Repository\MotRepository;
use App\Form\MotType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/mot")
 */
class MotController extends AbstractController
{
    /**
     * @Route("/", name="mot_index", methods={"GET"})
     */
    public function index(MotRepository $motRepository): Response
    {
        return $this->render('mot/liste_mots.html.twig', [
            'mots' => $motRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="mot_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mot = new Mot();
        $form = $this->createForm(MotType::class, $mot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mot);
            $entityManager->flush();

            return $this->redirectToRoute('mot_index');
        }

        return $this->render('mot/nouveau_mot.html.twig', [
            'mot' => $mot,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/mot/{id}/modifier", name="mot_modifier")
     */
    public function modifier(Request $request, Mot $mot, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(MotType::class, $mot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Le mot a été modifié.');

            return $this->redirectToRoute('mot_index');
        }

        return $this->render('mot/modifier_mot.html.twig', [
            'form' => $form->createView(),
            'mot' => $mot
        ]);
    }

}