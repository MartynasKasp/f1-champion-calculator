<?php

namespace App\Controller\Admin;

use App\Service\RaceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route(
        path: '/admin/dashboard',
        name: 'admin_dashboard'
    )]
    public function dashboard(
        RaceManager $raceManager,
    ) {
        return $this->render('admin/dashboard/index.html.twig', [
            'nextRace' => $raceManager->getNextRaceForSeason()
        ]);
    }
}
