<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoviesController extends AbstractController
{
    #[Route('/movies/{id}/show', name: 'movies')]
    public function index($id,MovieRepository $movieRepository): Response
    {
        $movie = $movieRepository->find(['id' => $id]);
        if(!$movie)
        {
            return $this->redirectToRoute('home');
        }
        return $this->render('movies/index.html.twig', [
            'movie' => $movie,
        ]);
    }
}
