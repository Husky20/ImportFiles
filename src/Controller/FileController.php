<?php

namespace App\Controller;

use App\Form\ImportFileType;
use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileController extends AbstractController
{
    /**
     *
     * @return Response HTTP response
     */
    #[Route('/import-file', name: 'import_file')]
    public function success(): Response
    {
        return $this->render(
            'import_file/success.html.twig'
        );
    }

    #[Route("/import-file/new", name: 'import_file_new')]
    public function import(Request $request, FileService $importFileService): Response
    {
        $form = $this->createForm(ImportFileType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('csvFile')->getData();

            try {
                $importFileService->saveFile($file);
                $this->addFlash('success', 'The CSV file was saved.');

                return $this->redirectToRoute('import_file');
            } catch (FileException $exception) {
                $this->addFlash('error', 'An error occurred while saving the file.');
            }
        }

        return $this->render('import_file/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}