<?php
// src/Controller/StudentController.php
namespace App\Controller;

use App\Entity\Student;
use App\Entity\Notes;
use App\Entity\User;
use App\Entity\NotesDetails;
use App\Entity\Classe;
use App\Service\StudentNotesService;
use App\Repository\StudentRepository;
use App\Repository\UserRepository;
use App\Repository\ClasseRepository;
use App\Repository\NotesRepository;
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

    #[Route('/consult-notes', name: 'app_consult_note_page')]
    public function notes_page(StudentNotesService $studentNotesService, UserRepository $userRepository, NotesRepository $notesRepository, ClasseRepository $classeRepository, StudentRepository $studentRepository): Response
    {
        $student = $studentRepository->findOneByEmail($this->getUser()->getEmail());
        $student_name = $student->getPrenom(). ' ' .$student->getNom();
        $moyenne_generale = $studentNotesService->getMoyenneGenerale($student->getId(), $notesRepository);

        $notes = $notesRepository->findByStudentId($student->getId());

        $notesDetails = [];

        foreach ($notes as $note) {
            $class_id = $note->getClassId();
            $classe = $classeRepository->findOneByClassId($class_id);
            $professor = $userRepository->getUserbyId($classe->getProfesseurId());

            $notes_d = new NotesDetails();
            $notes_d->setNotes($note);
            $notes_d->setClasse($classe);
            $notes_d->setProfessorName($professor->getUsername());
            array_push($notesDetails, $notes_d);
        }

        return $this->render('student/consult-notes.html.twig',[
                        'student_name' => $student_name,
                        'informations' => $notesDetails,
                        'moy_gen' => number_format($moyenne_generale, 2, '.', ''),
        ]);
    }

    #[Route('/consult-notes/trimestre/{id}', name: 'app_consult_note_trimestre_page')]
    public function notes_trimestre_page(int $id, StudentNotesService $studentNotesService, UserRepository $userRepository, NotesRepository $notesRepository, ClasseRepository $classeRepository, StudentRepository $studentRepository): Response
    {
        $student = $studentRepository->findOneByEmail($this->getUser()->getEmail());
        $student_name = $student->getPrenom(). ' ' .$student->getNom();
        $moyenne_generale = $studentNotesService->getMoyenneTrimestreGenerale($student->getId(), $notesRepository, $classeRepository, $id);

        $notes = $notesRepository->findByStudentId($student->getId());

        $notesDetails = [];

        foreach ($notes as $note) {
            $class_id = $note->getClassId();
            $classe = $classeRepository->findOneByClassId($class_id);
            $professor = $userRepository->getUserbyId($classe->getProfesseurId());
            if($classe->getTrimestre()==$id){
            $notes_d = new NotesDetails();
            $notes_d->setNotes($note);
            $notes_d->setClasse($classe);
            $notes_d->setProfessorName($professor->getUsername());
            array_push($notesDetails, $notes_d);
            }
        }

        return $this->render('student/consult-notes-trimestre.html.twig',[
                        'id' => $id,
                        'student_name' => $student_name,
                        'informations' => $notesDetails,
                        'moy_gen' => number_format($moyenne_generale, 2, '.', ''),
        ]);
    }

    #[Route('/consult-bulletin', name: 'app_consult_bulletin_page')]
    public function notes_page(StudentNotesService $studentNotesService, UserRepository $userRepository, NotesRepository $notesRepository, ClasseRepository $classeRepository, StudentRepository $studentRepository): Response
    {

    }
}