<?php declare(strict_types=1);
namespace App\Controller;

use App\Entity\Student;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_index')]
    public function index(StudentRepository $studentRepository): Response
    {
        $studentList = $studentRepository->findAllStudents();

        return $this->render('main/index.html.twig', [
            'controller_name' => 'StudentsController',
            'students' => $studentList,
        ]);
    }
}
