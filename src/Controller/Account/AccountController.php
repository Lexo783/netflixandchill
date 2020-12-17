<?php

namespace App\Controller\Account;


use App\Repository\ProfilRepository;
use App\Services\Profile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function chooseProfil(ProfilRepository $profilRepository):Response{
        $userProfil = $profilRepository->findBy(['user'=>$this->getUser()]);
        return $this->render('account/profil/chooseProfil.html.twig',[
        'Profils' => $userProfil
        ]);
    }

    /**
     * @Route("/profile/setProfil/{id}", name="setprofile")
     */
    public function setProfile(ProfilRepository $profilRepository,$id,Profile $profilService):Response{

        $profil = $profilRepository->findOneById($id);
        if (!$profil){
            return $this->redirectToRoute('profile');
        }
        $profilService->setSessionProfile($profil);
        return $this->redirectToRoute('home');
    }
}
