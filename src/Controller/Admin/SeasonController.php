<?php

namespace App\Controller\Admin;

use App\Annotation\MenuItem;
use App\Form\SeasonEditFormType;
use App\Service\SeasonManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SeasonController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route(path: '/admin/seasons', name: 'admin_seasons_list')]
    #[MenuItem(label: 'Seasons', icon: 'fas fa-globe fa-fw', priority: 20)]
    public function list(
        SeasonManager $seasonManager,
    ) {
        return $this->render('admin/seasons/index.html.twig', [
            'seasons' => $seasonManager->getAllSeasonsSorted(),
        ]);
    }

    #[Route(path: '/admin/seasons/{id}/edit', name: 'admin_seasons_edit')]
    public function edit(
        string $id,
        Request $request,
        SeasonManager $seasonManager
    ) {
        $season = $seasonManager->findSeasonById($id);
        if (null === $season) {
            // TODO handle alerts
            $this->addFlash('error', 'Season does not exist.');
            return $this->redirectToRoute('admin_seasons_list');
        }

        $form = $this->createForm(SeasonEditFormType::class, $season);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Season info has been updated successfully.');
            return $this->redirectToRoute('admin_seasons_list');
        }

        return $this->render('admin/seasons/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
