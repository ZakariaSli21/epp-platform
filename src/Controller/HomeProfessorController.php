<?php
// src/Controller/HomeProfessorController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeProfessorController extends AbstractController
{
    #[Route('/professor-home', name: 'app_home_professor_page')]
    public function page(): Response
    {
        return $this->render('professor/index.html.twig');
    }
}

