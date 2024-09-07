<?php

namespace App\Controller\Admin;

use App\Service\RaceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Annotation\MenuItem;

class DashboardController extends AbstractController
{
    #[Route(
        path: '/admin/dashboard',
        name: 'admin_dashboard'
    )]
    #[MenuItem(label: 'Dashboard', icon: 'fas fa-chart-area fa-fw', priority: 10)]
    public function dashboard(
        RaceManager $raceManager,
    ) {
        return $this->render('admin/dashboard/index.html.twig', [
            'nextRace' => $raceManager->getNextRaceForSeason()
        ]);
    }
}
