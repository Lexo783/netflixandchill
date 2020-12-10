<?php

namespace App\Controller\Account;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    /**
     * AccountPasswordController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/account/password", name="account_password")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $notification = null;
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $oldPassword = $form->get('oldPassword')->getData();

            if ($passwordEncoder->isPasswordValid($user,$oldPassword))
            {
                $newPassword = $form->get('newPassword')->getData();
                $newPasswordConfirm = $form->get('newPasswordConfirm')->getData();

                if($newPassword == $newPasswordConfirm) {
                    $user->setPassword($passwordEncoder->encodePassword($user, $newPassword));
                    $this->entityManager->flush();
                    $notification = "le mot de passe a été mis a jour";

                    return $this->render('account/index.html.twig', [
                        'controller_name' => 'AccountController',
                    ]);
                }
                else{
                    $notification = "Erreur de mot de passe";
                }
            }
            else
            {
                $notification = "le mot de passe n'est pas le bon";
            }
        }

        return $this->render('account/account.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
        ]);
    }
}
