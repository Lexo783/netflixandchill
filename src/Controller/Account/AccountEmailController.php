<?php

namespace App\Controller\Account;

use App\Form\ChangeMailType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountEmailController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    /**
     * AccountAddressController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/account/mail", name="account_mail")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $notification = null;
        $user = $this->getUser();
        $form = $this->createForm(ChangeMailType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $newEmail = $form->get('newEmail')->getData();

            $user->setEmail($newEmail);
            $this->entityManager->flush();
            $notification = "L'email a bien été changée";

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
