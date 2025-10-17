<?php

namespace App\Controller\Admin;

use App\Service\DataImportManager;
use App\Trait\LoggerInjector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class DataController extends AbstractController
{
    use LoggerInjector;

    #[Route(path: '/admin/data/import-season', name: 'admin_data_import_season')]
    public function importData(Request $request, DataImportManager $dataImportManager)
    {
        try {
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $request->files->get('file');
            if (! $file->isValid()) {
                throw new \Exception('Invalid file');
            }

            $dataImportManager->importSeasonData($file);
            $this->addFlash('error', 'Data imported successfully');
        } catch (\Throwable $error) {
            $this->logger->error('Season data import failed. Error: ' . $error->getMessage());

            $this->addFlash('error', 'File import failed.');
            return $this->redirectToRoute('admin_seasons_list');
        }

        return $this->redirectToRoute('admin_seasons_list');
    }
}
