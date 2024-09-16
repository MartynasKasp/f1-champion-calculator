<?php

namespace App\Controller\Admin;

use App\Annotation\MenuItem;
use App\Entity\Season;
use App\Form\SeasonActionFormType;
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
    #[MenuItem(label: 'Seasons', icon: 'fas fa-globe', priority: 20)]
    public function list(
        SeasonManager $seasonManager,
    ) {
        // TODO pagination
        return $this->render('admin/seasons/index.html.twig', [
            'seasons' => $seasonManager->getAllSeasonsSorted(),
        ]);
    }

    #[Route(path: '/admin/seasons/create', name: 'admin_seasons_create')]
    public function create(
        Request $request,
    ) {
        $season = new Season();
        $form = $this->createForm(SeasonActionFormType::class, $season);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $season->setId($season->getStartsAt()->format('Y'));

            $this->entityManager->persist($season);
            $this->entityManager->flush();

            $this->addFlash('success', 'Season has been created successfully.');
            return $this->redirectToRoute('admin_seasons_list');
        }

        return $this->render('admin/seasons/action.html.twig', [
            'form' => $form->createView()
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

        $form = $this->createForm(SeasonActionFormType::class, $season);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Season info has been updated successfully.');
            return $this->redirectToRoute('admin_seasons_list');
        }

        return $this->render('admin/seasons/action.html.twig', [
            'form' => $form->createView(),
            'objectId' => $id
        ]);
    }
}
