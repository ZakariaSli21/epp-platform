<?php

// src/Service/MessageGenerator.php
namespace App\Service;

use App\Repository\StudentRepository;
use App\Repository\ClasseRepository;
use App\Repository\NotesRepository;
use App\Entity\Student;

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
}