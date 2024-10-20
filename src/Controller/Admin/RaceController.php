<?php

namespace App\Controller\Admin;

use App\Annotation\MenuItem;
use App\Entity\Race;
use App\Entity\RaceResult;
use App\Form\RaceActionFormType;
use App\Form\RaceResultActionFormType;
use App\Service\RaceManager;
use App\Service\RaceResultManager;
use App\Service\SeasonManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class RaceController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route(path: '/admin/races', name: 'admin_races_list')]
    #[MenuItem(label: 'Races', icon: 'fas fa-calendar', priority: 30)]
    public function list(
        Request $request,
        RaceManager $raceManager,
        SeasonManager $seasonManager,
    ) {
        $filters = $request->query->all('filters');
        if (!isset($filters['year'])) {
            $filters['year'] = (new \DateTimeImmutable())->format('Y');
        }

        // TODO pagination
        return $this->render('admin/races/index.html.twig', [
            'races' => $raceManager->getFilteredRaces($filters),
            'availableYears' => $seasonManager->getAvailableSeasons(),
            'availableTypes' => RaceManager::getAvailableTypes(),
        ]);
    }

    #[Route(path: '/admin/races/create', name: 'admin_races_create')]
    public function create(
        Request $request,
    ) {
        $race = new Race();
        $form = $this->createForm(RaceActionFormType::class, $race);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            for ($i = 1; $i <= 20; $i++) {
                $result = (new RaceResult())
                    ->setPosition($i)
                    ->setPoints(0);

                $this->entityManager->persist($result);
                $race->addResult($result);
            }

            $this->entityManager->persist($race);
            $this->entityManager->flush();

            $this->addFlash('success', 'Race has been created successfully.');
            return $this->redirectToRoute('admin_races_list');
        }

        return $this->render('admin/races/action.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route(path: '/admin/races/{id}/edit', name: 'admin_races_edit')]
    public function edit(
        string $id,
        Request $request,
        RaceManager $raceManager,
    ) {
        $race = $raceManager->findRaceById($id);
        if (null === $race) {
            // TODO handle alerts
            $this->addFlash('error', 'Race does not exist.');
            return $this->redirectToRoute('admin_races_list');
        }

        $form = $this->createForm(RaceActionFormType::class, $race);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Race info has been updated successfully.');
            return $this->redirectToRoute('admin_races_edit', ['id' => $id]);
        }

        $raceResults = $race->getResults()->toArray();
        uasort(
            $raceResults,
            fn (RaceResult $l, RaceResult $r) => $l->getPosition() > $r->getPosition() ? 1 : -1
        );

        return $this->render('admin/races/action.html.twig', [
            'form' => $form->createView(),
            'results' => $raceResults,
            'objectId' => $id,
        ]);
    }

    #[Route(path: '/admin/races/{raceId}/results/create', name: 'admin_races_create_results')]
    public function createResults(
        string $raceId,
        Request $request,
        RaceManager $raceManager,
    ) {
        $race = $raceManager->findRaceById($raceId);
        if (null === $race) {
            // TODO handle alerts
            $this->addFlash('error', 'Race does not exist.');
            return $this->redirectToRoute('admin_races_list');
        }

        $raceResult = new RaceResult();
        $raceResult
            ->setRace($race)
            ->setSeason($race->getSeason());

        $form = $this->createForm(RaceResultActionFormType::class, $raceResult, ['positionDisabled' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($raceResult);
            $this->entityManager->flush();

            $this->addFlash('success', 'Race result has been added successfully.');
            return $this->redirectToRoute('admin_races_edit', ['id' => $raceId]);
        }

        return $this->render('admin/races/results/action.html.twig', [
            'form' => $form->createView(),
            'raceId' => $raceId,
        ]);
    }

    #[Route(path: '/admin/races/{raceId}/results/{resultId}', name: 'admin_races_edit_results')]
    public function editResults(
        string $raceId,
        string $resultId,
        Request $request,
        RaceResultManager $raceResultManager,
    ) {
        $raceResult = $raceResultManager->findById($resultId);
        if (null === $raceResult) {
            // TODO handle alerts
            $this->addFlash('error', 'Race result does not exist.');
            return $this->redirectToRoute('admin_races_edit', ['id' => $raceId]);
        }

        $form = $this->createForm(RaceResultActionFormType::class, $raceResult);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Race result has been updated successfully.');
            return $this->redirectToRoute('admin_races_edit', ['id' => $raceId]);
        }

        return $this->render('admin/races/results/action.html.twig', [
            'form' => $form->createView(),
            'objectId' => $resultId,
            'raceId' => $raceId,
        ]);
    }
}
