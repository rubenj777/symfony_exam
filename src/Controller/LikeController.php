<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Impression;
use App\Entity\Like;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    /**
     * @Route("/film/like/{id}", name="like_film")
     * @param Film $film
     * @param EntityManagerInterface $manager
     * @param LikeRepository $repo
     * @return Response
     */
    public function likeFilm(Film $film, EntityManagerInterface $manager, LikeRepository $repo): Response
    {
        $like = $repo->findOneBy([
            'user' => $this->getUser(),
            'film' => $film
        ]);

        if ($like) {
            $manager->remove($like);
            $liked = false;
        } else {
            $like = new Like();
            $like->setUser($this->getUser());
            $like->setFilm($film);
            $manager->persist($like);
            $liked = true;
        }
        $manager->flush();

        $count = $repo->count(['film'=>$film]);

        $res = [
            'count'=>$count,
            'liked'=>$liked,
        ];

        return $this->json($res, 200);
    }

    /**
     * @Route("/impression/like/{id}", name="like_impression")
     * @param Impression $impression
     * @param EntityManagerInterface $manager
     * @param LikeRepository $repo
     * @return Response
     */
    public function likeImpression(Impression $impression, EntityManagerInterface $manager, LikeRepository $repo): Response
    {
        $like = $repo->findOneBy([
            'user' => $this->getUser(),
            'impression' => $impression
        ]);

        if ($like) {
            $manager->remove($like);
            $liked = false;
        } else {
            $like = new Like();
            $like->setUser($this->getUser());
            $like->setImpression($impression);
            $manager->persist($like);
            $liked = true;
        }
        $manager->flush();

        $count = $repo->count(['impression'=>$impression]);

        $res = [
            'count'=>$count,
            'liked'=>$liked,
        ];

        return $this->json($res, 200);
    }
}
