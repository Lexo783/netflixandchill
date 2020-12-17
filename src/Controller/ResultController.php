<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\MovieRepository;
use App\Services\Search;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResultController extends AbstractController
{
    #[Route('/result/{searchUrl}', name: 'result')]
    public function index($searchUrl,Request $request,MovieRepository $movieRepository): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class,$search);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $movies = $movieRepository->findWithSearch($search);
        }
        else
        {
            $search->string = $searchUrl;
            $movies = $movieRepository->findWithSearch($search);
        }
        return $this->render('result/result.html.twig',[
            'movies' => $movies,
            'form' => $form->createView(),
        ]);
    }
}
