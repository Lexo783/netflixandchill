<?php

namespace App\Controller;

use App\Entity\Rate;
use App\Form\RateType;
use App\Repository\MovieRepository;
use App\Repository\RateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rate')]
class RateController extends AbstractController
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/new', name: 'rate_new', methods: ['POST'])]
    public function new(MovieRepository $movieRepository)
    {
        $value = $_POST['rate'];
        $movieId = $_POST['movieId'];
        $movieBDD = $movieRepository->find(['id' => $movieId]);
        if($value < 1 && $value > 5 && !$movieBDD)
        {
            return $this->json(serialize('error'));
        }
        else{
            $rate = new Rate();
            $rate->setUser($this->getUser());
            $rate->setMovie($movieBDD);
            $rate->setRate($value);
            //$this->entityManager->persist($rate);
            //$this->entityManager->flush();

            return $this->json(serialize("success"));
        }
    }

    #[Route('/getRate', name: 'get_rate', methods: ['POST'])]
    public function getRate(RateRepository $rateRepository)
    {
        $movieId = $_POST['movieId'];
        $rate = $rateRepository->findOneBy(['movie' => $movieId, 'user' => $this->getUser()]);
        if(!$rate)
        {
            return $this->json(serialize("error"));
        }
        return $this->json($rate->getRate());
    }

    #[Route('/{id}/edit', name: 'rate_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rate $rate): Response
    {
        $form = $this->createForm(RateType::class, $rate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rate_index');
        }

        return $this->render('rate/edit.html.twig', [
            'rate' => $rate,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'rate_delete', methods: ['DELETE'])]
    public function delete(Request $request, Rate $rate): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rate->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rate_index');
    }
}
