<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Student;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


#[Route('/document')]
final class DocumentController extends AbstractController
{
    #[Route(name: 'app_document_index', methods: ['GET'])]
    public function index(DocumentRepository $documentRepository): Response
    {
        return $this->render('document/index.html.twig', [
            'documents' => $documentRepository->findAll(),
        ]);
    }

    #[Route('/new/{studentId}', name: 'app_document_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager,
        #[Autowire('%kernel.project_dir%/var/uploads')] string $pdfDirectory,
        StudentRepository $studentRepository,
        int $studentId // Student ID from the route
    ): Response {
        // Fetch the student entity
        $student = $studentRepository->find($studentId);

        if (!$student) {
            throw $this->createNotFoundException('Student not found.');
        }

        // Create the document and associate it with the student
        $document = new Document();
        $document->setStudent($student); // Associate the student with the document

        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pdfFile = $form->get('pdfFile')->getData();

            if ($pdfFile) {
                // Ensure the upload directory exists
                if (!file_exists($pdfDirectory)) {
                    mkdir($pdfDirectory, 0777, true);
                }

                $originalFilename = pathinfo($pdfFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = date('Y-m-d') . '_' . $safeFilename . '_' . uniqid() . '.' . $pdfFile->guessExtension();

                try {
                    $pdfFile->move(
                        $pdfDirectory,
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Unable to upload PDF file.');
                    return $this->redirectToRoute('app_document_new', ['studentId' => $studentId]);
                }

                // dd($newFilename,$pdfFile);
                // Set the PDF file name in the document entity
                $document->setPdfFile($newFilename);
                $document->setStudentNumber($student->getIdentificationNumber());

                // dd($document);
            }

            $entityManager->persist($document);
            $entityManager->flush();

            $this->addFlash('success', 'Document uploaded successfully.');
            return $this->redirectToRoute('app_student_documents_show', ['id' => $studentId], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document/new.html.twig', [
            'document' => $document,
            'form' => $form,
            'student_id' => $studentId
        ]);
    }

    #[Route('/download-document/{filename}', name: 'app_document_download', methods: ['GET'])]
    public function downloadDocument(
        string $filename,
        #[Autowire('%kernel.project_dir%/var/uploads')] string $pdfDirectory
    ): Response {
        // Construct the full path to the file
        $filePath = $pdfDirectory . '/' . $filename;

        // Check if the file exists
        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('The file does not exist.');
        }

        // Create a response to stream the file
        $response = new BinaryFileResponse($filePath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $filename); // Open in the browser
        $response->headers->set('Content-Type', 'application/pdf');

        return $response;
    }

    #[Route('/{id}', name: 'app_student_documents_show', methods: ['GET'])]
    public function showStudentDocuments(
        int $id,
        DocumentRepository $documentRepository,
        StudentRepository $studentRepository
    ): Response {
        $docs = $documentRepository->findByStudentId($id);
        // dd($docs);
        $student = $studentRepository->getFullName($id);

        return $this->render('document/show_student_documents.html.twig', [
            'documents' => $docs,
            'student_name' => $student,
            'student_id' => $id
        ]);
    }

    #[Route('/{id}/edit', name: 'app_document_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_document_delete', methods: ['POST'])]
    public function delete(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $document->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($document);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
    }
}
