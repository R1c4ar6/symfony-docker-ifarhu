<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LuckyController extends AbstractController
{
    #[Route('/main/lucky')]
    public function getLuckyNumber(): Response
    {
        $number = random_int(0, 100);

        return $this->render('main/lucky.html.twig', [
            'number' => $number,
        ]);
    }
}
