<?php

namespace App\Controller\Account;

use App\Form\ChangePseudoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountPseudoController extends AbstractController
{
    private $entityManager;

    /**
     * AccountPasswordController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/account/pseudo", name="account_pseudo")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $notification = null;
        $user = $this->getUser();
        $form = $this->createForm(ChangePseudoType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $newPseudo = $form->get('newPseudo')->getData();

            $user->setPseudo($newPseudo);
            $this->entityManager->flush();
            $notification = "Le pseudo a bien été changé";

            return $this->render('account/index.html.twig', [
                'controller_name' => 'AccountController',
            ]);

        }

        return $this->render('account/account.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
        ]);
    }
}
