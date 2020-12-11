<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Repository\FavoriteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/favorite', name: 'favorite')]
    public function index(): Response
    {
        return $this->render('favorite/index.html.twig', [
            'controller_name' => 'FavoriteController',
        ]);
    }

    #[Route('/favorite/{filmId}', name: 'toggle_favorite')]
    public function toggleFavorite($filmId, FavoriteRepository $favoriteRepository, UserRepository $userRepository): bool
    {
        $user = $this->getUser();

        $getUser = $userRepository->find($user);

        $favoriteList = $user->getFavorites();

        $favorite = $favoriteRepository->findOneBy(
            [
                'user' => $user,
                'movie' => $filmId
            ]
        );

        if(!in_array($filmId, $favoriteList)) {
            $newFavorite = new Favorite();
            $newFavorite->setUser($getUser);
            $newFavorite->setMovie($filmId);

            $this->em->persist($newFavorite);
            $this->em->flush();

            return true;
        }
        else{
            $user->removeFavorite($favorite);

            return false;
        }
    }
}
