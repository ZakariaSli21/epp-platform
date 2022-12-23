<?php
// src/Controller/HomePController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function page(): Response
    {
        return $this->render('home/index.html.twig');
    }
}

