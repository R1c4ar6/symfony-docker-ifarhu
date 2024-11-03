<?php
namespace App\Controller;

use App\Entity\Student;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentsController extends AbstractController
{
    #[Route('/main/index', name: 'app_main_inicio')]
    public function index(): Response
    {
        $student = new Student();
        $studentsList = $student->getStudents();
        return $this->render('main/index.html.twig', [
            'controller_name' => 'StudentsController',
            'students' => $studentsList
        ]);
    }
}
