<?php

namespace App\Controller\Admin;

use App\Annotation\MenuItem;
use App\Entity\Circuit;
use App\Entity\Race;
use App\Form\CircuitActionFormType;
use App\Form\RaceActionFormType;
use App\Service\RaceManager;
use App\Service\SeasonManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        Request $request,
        RaceManager $raceManager,
        SeasonManager $seasonManager,
    ) {
        // $filters = $request->query->all('filters');
        // if (!isset($filters['year'])) {
        //     $filters['year'] = (new \DateTimeImmutable())->format('Y');
        // }

        // TODO pagination
        return $this->redirectToRoute('admin_dashboard');
        // return $this->render('admin/races/index.html.twig', [
        //     'races' => $raceManager->getFilteredRaces($filters),
        //     'availableYears' => $seasonManager->getAvailableSeasons(),
        //     'availableTypes' => RaceManager::getAvailableTypes(),
        // ]);
    }

    #[Route(path: '/admin/circuits/create', name: 'admin_circuits_create')]
    public function create(
        Request $request,
    ) {
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

    // #[Route(path: '/admin/races/{id}/edit', name: 'admin_races_edit')]
    // public function edit(
    //     string $id,
    //     Request $request,
    //     RaceManager $raceManager
    // ) {
    //     $race = $raceManager->findRaceById($id);
    //     if (null === $race) {
    //         // TODO handle alerts
    //         $this->addFlash('error', 'Race does not exist.');
    //         return $this->redirectToRoute('admin_races_list');
    //     }

    //     $form = $this->createForm(RaceActionFormType::class, $race);

    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->entityManager->flush();

    //         $this->addFlash('success', 'Race info has been updated successfully.');
    //         return $this->redirectToRoute('admin_races_list');
    //     }

    //     return $this->render('admin/races/action.html.twig', [
    //         'form' => $form->createView(),
    //         'objectId' => $id
    //     ]);
    // }
}
