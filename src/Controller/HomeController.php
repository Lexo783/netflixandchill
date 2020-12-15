<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use App\Services\Search;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request,MovieRepository $movieRepository, GenreRepository $genreRepository): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class,$search);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $movies = $movieRepository->findWithSearch($search);
        }
        else {
            $genres = $genreRepository->issetGenreAll();
        }
        return $this->render('home/index.html.twig', [
            'genres' => $genres
        ]);
    }
}
