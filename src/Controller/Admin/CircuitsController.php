<?php

namespace App\Controller\Admin;

use App\Annotation\MenuItem;
use App\Entity\Circuit;
use App\Form\CircuitActionFormType;
use App\Service\CircuitManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CircuitsController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route(path: '/admin/circuits', name: 'admin_circuits_list')]
    #[MenuItem(label: 'Circuits', icon: 'far fa-folder', priority: 40)]
    public function list(
        CircuitManager $circuitManager,
    ): Response {
        return $this->render('admin/circuits/index.html.twig', [
            'circuits' => $circuitManager->getAllCircuits(),
        ]);
    }

    #[Route(path: '/admin/circuits/create', name: 'admin_circuits_create')]
    public function create(
        Request $request,
    ): Response {
        $circuit = new Circuit();
        $form = $this->createForm(CircuitActionFormType::class, $circuit);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($circuit);
            $this->entityManager->flush();

            $this->addFlash('success', 'Circuit has been created successfully.');
            return $this->redirectToRoute('admin_circuits_list');
        }

        return $this->render('admin/circuits/action.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route(path: '/admin/circuits/{id}/edit', name: 'admin_circuits_edit')]
    public function edit(
        string $id,
        Request $request,
        CircuitManager $circuitManager,
    ): Response {
        $circuit = $circuitManager->findCircuitById($id);
        if (null === $circuit) {
            // TODO handle alerts
            $this->addFlash('error', 'Circuit does not exist.');
            return $this->redirectToRoute('admin_circuits_list');
        }
        $form = $this->createForm(CircuitActionFormType::class, $circuit);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Circuit has been edited successfully.');
            return $this->redirectToRoute('admin_circuits_list');
        }

        return $this->render('admin/circuits/action.html.twig', [
            'form' => $form->createView(),
            'objectId' => $id,
        ]);
    }
}
