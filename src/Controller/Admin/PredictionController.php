<?php

namespace App\Controller\Admin;

use App\Annotation\MenuItem;
use App\Service\CalculatorManager;
use App\Service\PredictionManager;
use App\Service\SeasonManager;
use App\Trait\LoggerInjector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PredictionController extends AbstractController
{
    use LoggerInjector;

    #[Route(
        path: '/admin/prediction-calculate',
        name: 'admin_prediction_calculate'
    )]
    public function runPrediction(
        CalculatorManager $calculatorManager
    ) {
        try {
            $calculatorManager->calculate();
            $this->addFlash('info', 'Prediction is now being calculated. This might take a few moments.');
        } catch (\Exception $exception) {
            $this->logger->error(
                'Error during prediction calculate: ' . $exception->getMessage(),
                ['route' => 'admin_prediction_calculate']
            );
            $this->addFlash('error', 'Oops! Something went wrong.');
        }

        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route(
        path: 'admin/predictions',
        name: 'admin_predictions_list'
    )]
    #[MenuItem(label: 'Predictions', icon: 'fas fa-chart-bar', priority: 70)]
    public function list(
        Request $request,
        SeasonManager $seasonManager,
        PredictionManager $predictionManager,
    ) {
        $filters = $request->query->all('filters');
        if (!isset($filters['season'])) {
            $filters['season'] = (new \DateTimeImmutable())->format('Y');
        }

        $predictions = $predictionManager->getFilteredPredictions();

        return $this->render('admin/predictions/index.html.twig', [
            'availableSeasons' => $seasonManager->getAvailableSeasons(),
            'predictions' => $predictions,
        ]);
    }
}
