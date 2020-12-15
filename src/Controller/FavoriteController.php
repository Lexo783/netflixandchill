<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\User;
use App\Repository\FavoriteRepository;
use App\Repository\MovieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/favorite', name: 'toggle_favorite')]
    public function toggleFavorite(FavoriteRepository $favoriteRepository, UserRepository $userRepository, MovieRepository $movieRepository): JsonResponse
    {
        $value = $_POST['favorite'];
        $movieId = $_POST['movieId'];

        $user = $this->getUser();
        $getUser = $userRepository->find($user);
        $getMovie = $movieRepository->find($movieId);

        if($value != 0 && $getMovie)
        {
            $newFavorite = new Favorite();
            $newFavorite->setUser($getUser);
            $newFavorite->setMovie($getMovie);

            $this->em->persist($newFavorite);
            $this->em->flush();

            return new JsonResponse(true);
        }

        $favorite = $favoriteRepository->findOneBy(
            [
                'user' => $user,
                'movie' => $movieId
            ]
        );

        $this->em->remove($favorite);
        $this->em->flush();

        return new JsonResponse(false);
    }

}
