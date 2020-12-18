<?php

namespace App\Controller;

use App\Repository\FavoriteRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoviesController extends AbstractController
{
    #[Route('/movies/show/{movieId}', name: 'movies')]
    public function index($movieId,MovieRepository $movieRepository, FavoriteRepository $favoriteRepository): Response
    {
        $movie = $movieRepository->find(['id' => $movieId]);
        $favorite = $favoriteRepository->findOneBy(['movie' => $movieId]);

        if (!$favorite) {
            $favorite = null;
        }

        if(!$movie)
        {
            return $this->redirectToRoute('home');
        }
        return $this->render('movies/index.html.twig', [
            'movie' => $movie,
            'favorite' => $favorite
        ]);
    }
}
