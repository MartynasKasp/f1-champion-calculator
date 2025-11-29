<?php

namespace App\Controller\Admin;

use App\Annotation\MenuItem;
use App\Service\CalculatorManager;
use App\Service\PredictionManager;
use App\Service\SeasonManager;
use App\Trait\LoggerInjector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    ): Response {
        try {
            $calculatorManager->calculate();
            $this->addFlash('info', 'Permutation is now being calculated. This might take a few moments.');
        } catch (\Exception $exception) {
            $this->logger->error(
                'Error during permutation calculate: ' . $exception->getMessage(),
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
    #[MenuItem(label: 'Permutations', icon: 'fas fa-chart-bar', priority: 70)]
    public function list(
        Request $request,
        SeasonManager $seasonManager,
        PredictionManager $predictionManager,
    ): Response {
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

    #[Route(
        path: 'admin/predictions/{id}',
        name: 'admin_predictions_view'
    )]
    public function view(
        string $id,
        PredictionManager $predictionManager,
    ): Response {
        $prediction = $predictionManager->findPredictionById($id);
        if (null === $prediction) {
            // TODO handle alerts
            $this->addFlash('error', 'Permutation does not exist.');
            return $this->redirectToRoute('admin_predictions_list');
        }

        $formattedPrediction = $predictionManager->getFormattedPredictionForDisplay($prediction);

        return $this->render('admin/predictions/view.html.twig', [
            'predictionDisplay' => $formattedPrediction,
        ]);
    }
}
