<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Form\ResetPasswordType;
use App\Repository\ResetPasswordRepository;
use App\Repository\UserRepository;
use App\Services\MailJetApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/reset/password", name="reset_password")
     */
    public function index(Request $request, UserRepository $userRepository,MailJetApi $mailJetApi): Response
    {
        if ($this->getUser())
        {
            return $this->redirectToRoute('home');
        }
        if ($request->get('email'))
        {
            $user = $userRepository->findOneBy(['email' => $request->get('email')]);
            if($user)
            {
                // 1: enregister en base le demande de reset
                $resetPassword = new ResetPassword();
                $resetPassword->setUser($user);
                $resetPassword->setToken(uniqid());
                $resetPassword->setCreatedAt(new \DateTime());
                $this->entityManager->persist($resetPassword);
                $this->entityManager->flush();

                // 2: Envoyez un email à l'utilisateur avec un lien de reset
                $url = $this->generateUrl('update_password', [
                    'token' => $resetPassword->getToken(),
                    ]);
                $content = "Bonjours".$user->getFirstName()."<br/> Vous avez demandé à réinitialiser votre mot de passe sur le site de la boutique cliqué <a href='.$url.'>ici</a>";
                $mailJetApi->send($user->getEmail(),$user->getFirstName(),'Réinitialiser votre mot de passe',$content);
                $this->addFlash('notice','Un mail a été envoyé');
            }
            else
            {
                $this->addFlash('notice','Adresse email non reconnue');

            }
        }
        return $this->render('reset_password/index.html.twig', [
            'controller_name' => 'ResetPasswordController',
        ]);
    }

    /**
     * @Route("/reset/password/{token}", name="update_password")
     */
    public function updatePassword($token,ResetPasswordRepository $resetPasswordRepository,
                                   Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $resetPassword = $resetPasswordRepository->findOneBy(['token' => $token]);
        if(!$resetPassword)
        {
            return $this->redirectToRoute('reset_password');
        }
        // Vérifier si le createdAt = now - 3h
        $now = new \DateTime();
        if ($now > $resetPassword->getCreatedAt()->modify('+ 3 hour'))
        {
            $this->addFlash('notice','votre demande de mot de passe a expiré');
            return $this->redirectToRoute('reset_password');
        }
        //rendre une vue
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $newPassword = $form->get('newPassword')->getData();
            $resetPassword->getUser()->setPassword($passwordEncoder->encodePassword($resetPassword->getUser(),$newPassword));

            $this->entityManager->flush();

            $this->addFlash('notice','Votre mot de passe à été mis a jour');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('reset_password/update.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
