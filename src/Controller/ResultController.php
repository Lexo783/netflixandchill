<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use App\Services\Search;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class ResultController extends AbstractController
{
    #[Route('/result', name: 'result',methods: ['post'])]
    public function index(MovieRepository $movieRepository,NormalizerInterface $normalizer)
    {
        $search = new Search();
        $search->string = $_POST['title'];
        $movies = $movieRepository->findWithSearch($search);
        $normalizerJson = $normalizer->normalize($movies,null,['groups' => 'result:movie']);
        return $this->json($normalizerJson,200,[
            "Content-Type" => "application/json"
        ]);
    }
}
