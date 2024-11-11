<?php declare(strict_types=1);
namespace App\Controller;

use App\Entity\Document;
use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DocumentController extends AbstractController
{
    #[Route('/documents/{id}', name: 'app_document_show', methods: ['GET'])]
    public function showDocuments(int $id,DocumentRepository $documentRepository): Response
    {
        $docs=$documentRepository->findByStudent($id);

        dd($docs);
        return $this->render('document/show.html.twig', [
            'controller_name' => 'DocumentController',
            'documents' => $docs,
        ]);
    }
}