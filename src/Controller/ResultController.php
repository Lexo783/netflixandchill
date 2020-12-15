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
    #[Route('/result', name: 'result')]
    public function index(Request $request,MovieRepository $movieRepository): Response
    {

        return $this->render('result/result.html.twig');
    }
}