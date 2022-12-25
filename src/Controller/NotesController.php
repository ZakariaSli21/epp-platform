<?php
// src/Controller/ProfessorController.php
namespace App\Controller;

use App\Service\ClassStatisticsService;
use App\Entity\Student;
use App\Entity\User;
use App\Entity\ClassEditNotes;
use App\Entity\Notes;
use App\Entity\Classe;
use App\Entity\ClassDetails;
use App\Form\StudentFormType;
use App\Form\CreateClassFormType;
use App\Form\EditClassInformationsFormType;
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

class NotesController extends AbstractController
{

    #[Route('/edit-class-informations/{id}', name: 'app_edit_class_informations')]
    public function edit_class_informations(Request $request, NotesRepository $notesRepository, ClasseRepository $classeRepository, StudentRepository $studentRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $class_name = $classeRepository->find($id)->getName();
        $notes = $notesRepository->findByClassId($id);
        $classDetails = [];
        $error=false;

        $classEditNotes = new ClassEditNotes();
        $form = $this->createForm(EditClassInformationsFormType::class, $classEditNotes);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $noteEntity = $notesRepository->findOneByClassId_StudentId($id,$classEditNotes->getStudentid());
            if($noteEntity == null)
            {
                   $error=true;
            }
            else
            {
                if($classEditNotes->getNote1()!==null){
                $noteEntity->setNote1($classEditNotes->getNote1());
                }
                if($classEditNotes->getNote2()!==null){
                $noteEntity->setNote2($classEditNotes->getNote2());
                }
                if($classEditNotes->getNote3()!==null){
                $noteEntity->setNote3($classEditNotes->getNote3());
                }
                if($classEditNotes->getCoef1()!==null){
                $noteEntity->setCoef1($classEditNotes->getCoef1());
                }
                if($classEditNotes->getCoef2()!==null){
                $noteEntity->setCoef2($classEditNotes->getCoef2());
                }
                if($classEditNotes->getCoef3()!==null){
                $noteEntity->setCoef3($classEditNotes->getCoef3());
                }
                if($classEditNotes->getAbsence()!==null){
                $noteEntity->setNbrAbsences($classEditNotes->getAbsence());
                }
                if($classEditNotes->getNote()==null){
                    $noteEntity->setNoteAutomaticaly();
                }
                else
                {
                    $noteEntity->setNote($classEditNotes->getNote());
                }
                if($classEditNotes->getNotecoef()!==null){
                $noteEntity->setCoefMoyenne($classEditNotes->getNotecoef());
                }
                if($classEditNotes->getCommentaire()!==null){
                $noteEntity->setProfessorCommentaire($classEditNotes->getCommentaire());
                }

                $entityManager->flush();

                return $this->redirectToRoute('app_edit_class_informations',array('id' => $id));
            }
        }


        foreach ($notes as $note) {
            $eleve_id = $note->getStudentId();
            $student = $studentRepository->findByOneStudentId($eleve_id);
            $classe_d = new ClassDetails();
            $classe_d->setNotes($note);
            $classe_d->setStudent($student);
            array_push($classDetails, $classe_d);
        }

        return $this->render('professor/edit-class-informations.html.twig',[
                        'id_class' => $id,
                        'class_name' => $class_name,
                        'error' => $error,
                        'informations' => $classDetails,
                        'EditForm' => $form->createView(),
        ]);
    }

    #[Route('/class-statistics/{id}', name: 'app_statistics_class')]
    public function statistics_class(ClassStatisticsService $classStatisticsService, ClasseRepository $classeRepository, StudentRepository $studentRepository, NotesRepository $notesRepository, int $id): Response
    {
        $class_name = $classeRepository->find($id)->getName();
        $note_max = $classStatisticsService->getMaxNoteInClass($id, $notesRepository);
        $note_min = $classStatisticsService->getMinNoteInClass($id, $notesRepository);
        $note_moy = $classStatisticsService->getMoyNoteInClass($id, $notesRepository);
        $student_max = $classStatisticsService->getStudentsWithMaxNoteNoteInClass($id, $notesRepository, $studentRepository);
        $student_min = $classStatisticsService->getStudentsWithMinNoteNoteInClass($id, $notesRepository, $studentRepository);
        $nbr_student = $classStatisticsService->getNbrEleveByClassId($id, $notesRepository);
        $nbr_students_reussite = $classStatisticsService->getNbrStudentSucceed($id, $notesRepository,10);
        $nbr_students_failed = $classStatisticsService->getNbrStudentFailed($id, $notesRepository,10);
        $perc_students_reussite = $classStatisticsService->getPercStudentSucceed($id, $notesRepository,10);
        $nbr_students_great_moy = $classStatisticsService->getNbrStudentSucceed($id, $notesRepository,$note_moy);
        $nbr_students_abs = $classStatisticsService->getNbrStudentsAbsent($id, $notesRepository);
        $stduent_great_abs = $classStatisticsService->getStudentWithMaxAbsence($id, $notesRepository, $studentRepository);

        return $this->render('professor/class-statistics.html.twig',[
                                'id_class' => $id,
                                'class_name' => $class_name,
                                'note_max' => $note_max,
                                'note_min' => $note_min,
                                'note_moy' => $note_moy,
                                'student_max' => $student_max,
                                'student_min' => $student_min,
                                'nbr_student' => $nbr_student,
                                'nbr_reu' => $nbr_students_reussite,
                                'nbr_echec' => $nbr_students_failed,
                                'perc_reu' => $perc_students_reussite,
                                'nbr_reu_moy' => $nbr_students_great_moy,
                                'nbr_abs' => $nbr_students_abs,
                                'student_abs' => $stduent_great_abs,
        ]);

    }

}