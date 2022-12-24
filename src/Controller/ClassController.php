<?php
// src/Controller/ClassController.php
namespace App\Controller;

use App\Entity\Student;
use App\Entity\User;
use App\Entity\Classe;
use App\Form\StudentFormType;
use App\Form\CreateClassFormType;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


class ClassController extends AbstractController
{
    #[Route('/create-class', name: 'app_create_class')]
    public function create_class(Request $request , EntityManagerInterface $entityManager): Response
    {
        $classe = new Classe();
        $form = $this->createForm(CreateClassFormType::class, $classe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
             $classe->setProfesseurId($this->getUser()->getId());

             $entityManager->persist($classe);
             $entityManager->flush();

             return $this->redirectToRoute('app_home_professor_page');
        }
        return $this->render('professor/create-class.html.twig', [
            'CreateClassForm' => $form->createView(),
        ]);
    }
}