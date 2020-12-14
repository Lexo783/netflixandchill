<?php

namespace App\Controller\Account\Profil;

use App\Entity\Profil;
use App\Form\ProfilType;
use App\Repository\ProfilRepository;
use App\Services\UploadFileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account/profil')]
class AccountProfilController extends AbstractController
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'profil_index', methods: ['GET'])]
    public function index(ProfilRepository $profilRepository): Response
    {
        return $this->render('account/profil/index.html.twig', [
            'profils' => $profilRepository->findBy(['user' => $this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'profil_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UploadFileService $uploadFileService): Response
    {
        $profil = new Profil();
        $form = $this->createForm(ProfilType::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $avatar = $form->get('picture')->getData();
            $control = $form->get('control')->getData();

            if ($avatar) {
                $avatarFileName = $uploadFileService->upload($avatar, "profil");
                $profil->setPicture($avatarFileName);
            }

            $profil->setControl($control != false ? 1 : 0);

            $profil->setUser($this->getUser());

            $this->em->persist($profil);
            $this->em->flush();

            return $this->redirectToRoute('profil_index');
        }

        return $this->render('account/profil/new.html.twig', [
            'profil' => $profil,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'profil_show', methods: ['GET'])]
    public function show(Profil $profil): Response
    {
        return $this->render('account/profil/show.html.twig', [
            'profil' => $profil,
        ]);
    }

    #[Route('/{id}/edit', name: 'profil_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Profil $profil, UploadFileService $uploadFileService): Response
    {
        $form = $this->createForm(ProfilType::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $avatar = $form->get('picture')->getData();
            $control = $form->get('control')->getData();

            if ($avatar) {
                $avatarFileName = $uploadFileService->upload($avatar, "profil");
                $profil->setPicture($avatarFileName);
            }

            $profil->setControl($control != false ? 1 : 0);

            $this->em->flush();

            return $this->redirectToRoute('profil_index');
        }

        return $this->render('account/profil/edit.html.twig', [
            'profil' => $profil,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'profil_delete', methods: ['DELETE'])]
    public function delete(Request $request, Profil $profil): Response
    {
        if ($this->isCsrfTokenValid('delete'.$profil->getId(), $request->request->get('_token'))) {
            $this->em->remove($profil);
            $this->em->flush();
        }

        return $this->redirectToRoute('profil_index');
    }
}
