<?php

namespace App\Controller\Admin;

use App\Service\RaceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Annotation\MenuItem;
use App\Entity\Prediction;
use App\Entity\Season;
use App\Service\RaceResultManager;
use Doctrine\ORM\EntityManagerInterface;

class DashboardController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route(
        path: '/admin/dashboard',
        name: 'admin_dashboard'
    )]
    #[MenuItem(label: 'Dashboard', icon: 'fas fa-chart-area', priority: 10)]
    public function dashboard(
        RaceManager $raceManager,
        RaceResultManager $raceResultManager,
    ) {
        /** @var \App\Repository\SeasonRepository $seasonRepository */
        $seasonRepository = $this->entityManager->getRepository(Season::class);
        /** @var \App\Repository\PredictionRepository $predictionRepository */
        $predictionRepository = $this->entityManager->getRepository(Prediction::class);

        $season = $seasonRepository->findSeasonInPeriod(new \DateTimeImmutable());
        $nextRace = $raceManager->getNextRaceForSeason($season);

        return $this->render('admin/dashboard/index.html.twig', [
            'nextRace' => $nextRace,
            'standings' => $raceResultManager->getDriversByStandingsForSeason($season),
            'season' => $season,
            'prediction' => $predictionRepository->findPredictionForRace($nextRace->getId()),
        ]);
    }
}
