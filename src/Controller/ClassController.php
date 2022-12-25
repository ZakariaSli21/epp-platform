<?php
// src/Controller/ClassController.php
namespace App\Controller;

use App\Entity\Student;
use App\Entity\User;
use App\Entity\Classe;
use App\Entity\ClassDetails;
use App\Entity\Notes;
use App\Form\StudentFormType;
use App\Form\CreateClassFormType;
use App\Form\AddStudentToClassFormType;
use App\Form\RemoveStudentFromClassFormType;
use App\Repository\StudentRepository;
use App\Repository\ClasseRepository;
use App\Repository\NotesRepository;
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

    #[Route('/class/{id}', name: 'app_consult_class_id')]
    public function class_id(NotesRepository $notesRepository, ClasseRepository $classeRepository, StudentRepository $studentRepository, int $id): Response
    {
        $class_name = $classeRepository->find($id)->getName();
        $notes = $notesRepository->findByClassId($id);
        $classDetails = [];

        foreach ($notes as $note) {
            $eleve_id = $note->getStudentId();
            $student = $studentRepository->findByOneStudentId($eleve_id);
            $classe_d = new ClassDetails();
            $classe_d->setNotes($note);
            $classe_d->setStudent($student);
            array_push($classDetails, $classe_d);
        }

        return $this->render('professor/consult-class.html.twig',[
                'id_class' => $id,
                'class_name' => $class_name,
                'notes' => $notes,
                'informations' => $classDetails,
        ]);
    }

    #[Route('/edit-class-students/{id}', name: 'app_edit_class_students')]
    public function edit_class_students(Request $request, ClasseRepository $classeRepository, StudentRepository $studentRepository, NotesRepository $notesRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        //variables pour afficher la liste des eleves de la classe
        $notes = $notesRepository->findByClassId($id);
        $classDetails = [];

        //variables et code pour ajouter ou supprimer un eleve de la classe
        $class_name = $classeRepository->find($id)->getName();

        $notes1 = new Notes();
        $notes2 = new Notes();
        $error1 = false; //l'email existe dans la liste des eleves
        $error2 = false; //l'email existe dans la liste des eleves
        $exist1 = false; //l'etudiant n'est pas inscrit dans la classe courante
        $exist2 = true;  //l'etudiant est inscrit dans la classe courante

        $student1 = new Student();
        $form1 = $this->createForm(AddStudentToClassFormType::class, $student1);
        $form1->handleRequest($request);

        $student2 = new Student();
        $form2 = $this->createForm(RemoveStudentFromClassFormType::class, $student2);
        $form2->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {
             $student_find1 = $studentRepository->findOneByEmail($student1->getEmail());

             if ($student_find1 == null)
             {
                  $error1 = true;
             }
             else {
                $student_id1 = $student_find1->getId();

                $student_exist1 = $notesRepository->findOneByClassId_StudentId($id,$student_id1);

                if ($student_exist1 !== null)
                {
                    $exist1 = true;
                }
                else
                {
                    $notes1->setClassId($id);
                    $notes1->setStudentId($student_id1);

                    $entityManager->persist($notes1);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_edit_class_students',array('id' => $id));
                }
             }
        }

        if ($form2->isSubmitted() && $form2->isValid()) {
             $student_find2 = $studentRepository->findOneByEmail($student2->getEmail());

             if ($student_find2 == null)
             {
                  $error2 = true;
             }
             else {
                $student_id2 = $student_find2->getId();
                $notes2 = $notesRepository->findOneByClassId_StudentId($id,$student_id2);
                //$notes2->setClassId($id);
                //$notes2->setStudentId($student_id2);
                if($notes2 == null){
                    $exist2=false;
                }
                else
                {
                    $notesRepository->remove($notes2, true);

                    return $this->redirectToRoute('app_consult_class_id',array('id' => $id));
                }
             }
        }

        //code d'affichage des eleves de la classe
        foreach ($notes as $note) {
                    $eleve_id = $note->getStudentId();
                    $student = $studentRepository->findByOneStudentId($eleve_id);
                    $classe_d = new ClassDetails();
                    $classe_d->setNotes($note);
                    $classe_d->setStudent($student);
                    array_push($classDetails, $classe_d);
        }

        return $this->render('professor/edit-class-students.html.twig',[
                'id_class' => $id,
                'AddStudentToClassForm' => $form1->createView(),
                'RemoveStudentToClassForm' => $form2->createView(),
                'class_name' => $class_name,
                'error1' => $error1,
                'error2' => $error2,
                'exist1' => $exist1,
                'exist2' => $exist2,
                'eleves' => $classDetails,

        ]);
    }
}