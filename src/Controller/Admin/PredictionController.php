<?php

namespace App\Controller\Admin;

use App\Service\CalculatorManager;
use App\Trait\LoggerInjector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
