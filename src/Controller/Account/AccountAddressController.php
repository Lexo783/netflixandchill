<?php

namespace App\Controller\Account;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use App\Services\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
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
     * @Route("/account/address", name="account_address")
     */
    public function index(): Response
    {
        return $this->render('account/address.html.twig', [
            'controller_name' => 'AccountAddressController',
        ]);
    }

    /**
     * @Route("/account/add", name="add_account_address")
     */
    public function add(Request $request, Cart $cart): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class,$address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $address->setUser($this->getUser());
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            if ($cart->getCart()){
                return $this->redirectToRoute('order');
            }
            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/{id}/edit", name="edit_account_address")
     */
    public function edit(Request $request, $id, AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findOneById($id);
        if (!$address || $address->getUser() != $this->getUser())
        {
            return $this->redirectToRoute('home');
        }
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->flush();
            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/{id}/delete", name="delete_account_address")
     */
    public function delete($id, AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findOneById($id);
        if ($address && $address->getUser() == $this->getUser())
        {
            $this->entityManager->remove($address);
            $this->entityManager->flush();
        }
        return $this->render('account/address.html.twig');
    }
}
