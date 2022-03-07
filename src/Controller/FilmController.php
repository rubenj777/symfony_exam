<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Impression;
use App\Form\FilmType;
use App\Form\ImpressionType;
use App\Repository\FilmRepository;
use App\Repository\ImpressionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     * @param FilmRepository $repo
     * @return Response
     */
    public function index(FilmRepository $repo): Response
    {
        $films = $repo->findAll();
        return $this->render('film/index.html.twig', [
            'films'=>$films
        ]);
    }

    /**
     * @Route("/film/{id}", name="show_film")
     * @param Film $film
     * @return Response
     */
    public function show(Film $film): Response
    {
        $impression = new Impression();
        $form = $this->createForm(ImpressionType::class, $impression);
        return $this->renderForm('film/show.html.twig', ['film'=>$film, 'form'=>$form]);
    }

    /**
     * @Route("/film/new", name="new_film", priority="2")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return RedirectResponse|Response
     */
    public function new(Request $request, EntityManagerInterface $manager)
    {
        $film = new Film();
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $film->setUser($this->getUser());
            $manager->persist($film);
            $manager->flush();
            return $this->redirectToRoute('index');
        }
        return $this->renderForm('film/new.html.twig', ['form'=>$form]);
    }

    /**
     * @Route("/film/delete/{id}", name="delete_film")
     * @param Film|null $film
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    public function delete(Film $film = null, EntityManagerInterface $manager): RedirectResponse
    {
        if($film && $film->getUser() === $this->getUser()) {
            $manager->remove($film);
            $manager->flush();
        }
        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/film/edit/{id}", name="edit_film")
     * @param Film $film
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return RedirectResponse|Response
     */
    public function edit(Film $film, Request $request, EntityManagerInterface $manager)
    {
        if($film->getUser() === $this->getUser()) {
            $form = $this->createForm(FilmType::class, $film);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $manager->persist($film);
                $manager->flush();
                return $this->redirectToRoute('show_film', ['id'=>$film->getId()]);
            }
            return $this->renderForm('film/edit.html.twig', ['form'=>$form]);
        }
    }
}
