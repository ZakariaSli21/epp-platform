<?php
// src/Controller/StudentController.php
namespace App\Controller;

use App\Entity\Student;
use App\Entity\User;
use App\Form\StudentFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class StudentController extends AbstractController
{
    #[Route('/parent-home', name: 'app_home_student_page')]
    public function home(): Response
    {
        return $this->render('student/index.html.twig');
    }
}