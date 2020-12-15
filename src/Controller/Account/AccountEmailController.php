<?php

namespace App\Controller\Account;

use App\Entity\ResetEmail;
use App\Form\ChangeMailType;
use App\Repository\ResetEmailRepository;
use App\Repository\UserRepository;
use App\Services\MailJetApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function index(Request $request,MailJetApi $mailJetApi): Response
    {
        $notification = null;
        $user = $this->getUser();
        $form = $this->createForm(ChangeMailType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // set reset Email Param's
            $resetEmail = new ResetEmail();
            $resetEmail->setUser($this->getUser());
            $resetEmail->setToken(uniqid());
            $resetEmail->setCreatedAt(new \DateTime());
            $resetEmail->setFuturEmail($form->get('newEmail')->getData());
            $this->entityManager->persist($resetEmail);
            $this->entityManager->flush();

            // gen
            $url = $this->generateUrl('account_reset_mail', [
                'token' => $resetEmail->getToken(),
            ]);
            $content = "je suis <a href='.$url.'>ici</a> avec mon token <br/>";
            $mailJetApi->send($this->getUser()->getEmail(),$this->getUser()->getPseudo(),"Changement Email",$content);

            return $this->render('account/index.html.twig', [
                'controller_name' => 'AccountController',
            ]);

        }

        return $this->render('account/account.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
        ]);
    }

    /**
     * @Route("/account/mail/reset/{token}", name="account_reset_mail")
     * @param Request $request
     * @return Response
     */
    public function valideRequest($token,ResetEmailRepository $emailRepository,UserRepository $userRepository)
    {
        $resetEmail = $emailRepository->findOneBy(['token' => $token]);
        if(!$resetEmail)
        {
            return $this->redirectToRoute('account_reset_mail');
        }
        // Vérifier si le createdAt = now - 3h
        $now = new \DateTime();
        if ($now > $resetEmail->getCreatedAt()->modify('+ 3 hour'))
        {
            $this->addFlash('notice','votre demande de mot de passe a expiré');
            return $this->redirectToRoute('account_mail');
        }

        $user = $this->getUser();
        $user->setEmail($resetEmail->getFuturEmail());
        $this->entityManager->flush();
        /*
        $user = $userRepository->find(['id' => $resetEmail->getUser()]);
        $user->setEmail($resetEmail->getFuturEmail());
        */
        return $this->redirectToRoute('account');
    }
}
