<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Impression;
use App\Form\ImpressionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImpressionController extends AbstractController
{
    /**
     * @Route("/impression/new/{id}", name="new_impression")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Film $film
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager, Film $film): Response
    {
        $impression = new Impression();
        $form = $this->createForm(ImpressionType::class, $impression);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $impression->setCreatedAt(new \DateTime());
            $impression->setFilm($film);
            $impression->setUser($this->getUser());
            $manager->persist($impression);
            $manager->flush();
        }
        return $this->redirectToRoute('show_film', ['id'=>$film->getId()]);
    }

    /**
     * @Route("/impression/delete/{id}", name="delete_impression")
     * @param Impression|null $impression
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    public function delete(Impression $impression = null, EntityManagerInterface $manager): RedirectResponse
    {
        if($impression && $impression->getUser() === $this->getUser()) {
            $manager->remove($impression);
            $manager->flush();
        }
        return $this->redirectToRoute('show_film', ['id'=>$impression->getFilm()->getId()]);
    }

    /**
     * @Route("/impression/edit/{id}", name="edit_impression")
     * @param Impression $impression
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return RedirectResponse|Response
     */
    public function edit(Impression $impression, Request $request, EntityManagerInterface $manager)
    {
        if($impression->getUser() === $this->getUser()) {
            $form = $this->createForm(ImpressionType::class, $impression);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $manager->persist($impression);
                $manager->flush();
                return $this->redirectToRoute('show_film', ['id'=>$impression->getFilm()->getId()]);
            }
            return $this->renderForm('impression/edit.html.twig', [
                'form'=>$form, 'impression'=>$impression,
            ]);
        }
    }

}
