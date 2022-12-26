<?php

// src/Service/StudentNotesService.php
namespace App\Service;

use App\Entity\Student;
use App\Repository\ClasseRepository;
use App\Repository\NotesRepository;
use App\Repository\UserRepository;
use App\Entity\NotesDetails;

class StudentNotesService
{

        public function getMoyenneGenerale($eleve_id, NotesRepository $notesRepository)
        {
                $notes = $notesRepository->findByStudentId($eleve_id);
                $coef_sum = 0;
                $note_total =0;

                foreach ($notes as $note)
                {
                        if (($note->getCoefMoyenne()==null)||($note->getCoefMoyenne()==0)){
                        $coef_sum += 1;
                        $coef = 1;
                        }
                        else {
                        $coef_sum += $note->getCoefMoyenne();
                        $coef = $note->getCoefMoyenne();
                        }
                        $note_total += ($coef * $note->getNote());
                }
                if($coef_sum ==0) {
                $coef_sum =1;
                }

                return $note_total/$coef_sum;
        }

        public function getMoyenneTrimestreGenerale($eleve_id, NotesRepository $notesRepository, ClasseRepository $classeRepository, int $trimestre)
        {
                $notes = $notesRepository->findByStudentId($eleve_id);
                $coef_sum = 0;
                $note_total =0;

                foreach ($notes as $note)
                {
                        $classe = $classeRepository->findOneByClassId($note->getClassId());
                        if($classe->getTrimestre()==$trimestre)
                        {
                            if (($note->getCoefMoyenne()==null)||($note->getCoefMoyenne()==0)){
                            $coef_sum += 1;
                            $coef = 1;
                            }
                            else {
                            $coef_sum += $note->getCoefMoyenne();
                            $coef = $note->getCoefMoyenne();
                            }
                            $note_total += ($coef * $note->getNote());
                        }
                }
                if($coef_sum ==0) {
                $coef_sum =1;
                }

                return $note_total/$coef_sum;
        }

        public function getNotesDetailsByTrimestre(ClasseRepository $classeRepository, UserRepository $userRepository, int $id, $notes): array
        {
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
                 return $notesDetails;
        }

        public function getNotesDetails(ClasseRepository $classeRepository, UserRepository $userRepository, $notes): array
        {
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
                 return $notesDetails;
        }

}