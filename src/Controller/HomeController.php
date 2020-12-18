<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(GenreRepository $genreRepository): Response
    {
        $genres = $genreRepository->issetGenreAll();
        return $this->render('home/index.html.twig', [
            'genres' => $genres,
        ]);
    }
}
