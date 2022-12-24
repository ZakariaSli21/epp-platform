<?php
// src/Controller/ProfessorController.php
namespace App\Controller;

use App\Entity\Student;
use App\Entity\User;
use App\Entity\Classe;
use App\Form\StudentFormType;
use App\Form\CreateClassFormType;
use App\Repository\StudentRepository;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfessorController extends AbstractController
{
    #[Route('/professor-home', name: 'app_home_professor_page')]
    public function home(ClasseRepository $classeRepository): Response
    {
        $id_professor = $this->getUser()->getId();
        $classe = $classeRepository->findBy(['professeur_id' => $id_professor]);;
        return $this->render('professor/index.html.twig',[
                'classes' => $classe,
        ]);
    }

    #[Route('/professor-add-student', name: 'app_home_professor_add_student')]
    public function add_student(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $student = new Student();
        $user = new User();
        $form = $this->createForm(StudentFormType::class, $student);
        $form->handleRequest($request);
        $user->setRoles(['ROLE_STUDENT']);

        if ($form->isSubmitted() && $form->isValid()) {
             $student = $form->getData();

             $username_construct = $student->constructUsername();
             $email_construct = $student->constructEmail();
             $random_psw = $student->generateRandomString(10);

             $user->setUsername($username_construct);
             $student->setUsername($username_construct);
             $user->setEmail($email_construct);
             $student->setEmail($email_construct);
             $student->setPassword($random_psw);
             // encode the student password
             $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $random_psw
                )
             );


             $entityManager->persist($student);
             $entityManager->flush();

             $entityManager->persist($user);
             $entityManager->flush();

             return $this->redirectToRoute('app_students_list');
        }

        return $this->render('professor/add-student.html.twig', [
            'StudentForm' => $form->createView(),
            ]);
    }

    #[Route('/students-list', name: 'app_students_list')]
    public function students_list(StudentRepository $studentRepository): Response
    {
        $student = $studentRepository->findAll();
        return $this->render('professor/students-list.html.twig', [
                'students' => $student,
        ]);
    }
}

