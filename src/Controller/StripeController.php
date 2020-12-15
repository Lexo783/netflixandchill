<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

#[Route('/stripe', name: 'stripe')]
class StripeController extends AbstractController
{
    /**
     * @Route("/", name="stripe_index")
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->render('stripe/index.html.twig');
    }

    /**
     * @Route("/verify", options={"expose"="true"}, name="stripe_verify")
     * @param Request $request
     * @return Response
     */
    public function verifyAction(Request $request): Response
    {
        return $this->render('stripe/verify.html.twig');
    }

    /**
     * @Route("/payment", name="stripe_payment")
     * @param Request $request
     * @return Response
     */
    public function paymentAction(Request $request): Response
    {

        $form = $this->get('form.factory')
            ->createNamedBuilder('payment-form')
            ->add('token', HiddenType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('submit', SubmitType::class)
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                // TODO: charge the card
            }
        }

        return $this->render('stripe/payment.html.twig', [
            'form' => $form->createView(),
            'stripe_public_key' => $this->getParameter('stripe_public_key'),
        ]);
    }
}