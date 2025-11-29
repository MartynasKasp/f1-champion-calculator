<?php

namespace App\Controller\Admin;

use App\Annotation\MenuItem;
use App\Entity\Team;
use App\Form\TeamActionFormType;
use App\Service\TeamManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConstructorsController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route(path: '/admin/constructors', name: 'admin_constructors_list')]
    #[MenuItem(label: 'Constructors', icon: 'fas fa-building', priority: 80)]
    public function list(
        TeamManager $manager,
    ): Response {
        return $this->render('admin/constructors/index.html.twig', [
            'teams' => $manager->getAllTeams(),
        ]);
    }

    #[Route(path: '/admin/constructors/create', name: 'admin_constructors_create')]
    public function create(
        Request $request,
    ): Response {
        $object = new Team();
        $form = $this->createForm(TeamActionFormType::class, $object);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($object);
            $this->entityManager->flush();

            $this->addFlash('success', 'Constructor has been created successfully.');
            return $this->redirectToRoute('admin_constructors_list');
        }

        return $this->render('admin/constructors/action.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route(path: '/admin/constructors/{id}/edit', name: 'admin_constructors_edit')]
    public function edit(
        string $id,
        Request $request,
        TeamManager $manager,
    ): Response {
        $object = $manager->findTeamById($id);
        if (null === $object) {
            // TODO handle alerts
            $this->addFlash('error', 'Constructor does not exist.');
            return $this->redirectToRoute('admin_constructors_list');
        }
        $form = $this->createForm(TeamActionFormType::class, $object);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Constructor has been edited successfully.');
            return $this->redirectToRoute('admin_constructors_list');
        }

        return $this->render('admin/constructors/action.html.twig', [
            'form' => $form->createView(),
            'objectId' => $id,
        ]);
    }
}
