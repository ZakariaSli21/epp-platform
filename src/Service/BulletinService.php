<?php

// src/Service/BulletinService.php
namespace App\Service;

use App\Repository\StudentRepository;
use App\Repository\ClasseRepository;
use App\Repository\NotesRepository;
use App\Repository\UserRepository;
use App\Entity\Student;
use App\Entity\NoteDetails;
use App\Service\StudentNotesService;
use App\Service\ClassStatisticsService;

class BulletinService
{
        public function getJsonBulletin(StudentNotesService $studentNotesService, ClassStatisticsService $classStatisticsService, ClasseRepository $classeRepository, UserRepository $userRepository, notesRepository $notesRepository, $notes): array
        {
            $noteDetails = $studentNotesService->getNotesDetails($classeRepository, $userRepository, $notes);

            $jsonBulletin = [];

            foreach($noteDetails as $note)
            {
                $max_class = $classStatisticsService->getMaxNoteInClass($note->getClasse()->getId(), $notesRepository);
                $min_class = $classStatisticsService->getMinNoteInClass($note->getClasse()->getId(), $notesRepository);
                $moy_class = $classStatisticsService->getMoyNoteInClass($note->getClasse()->getId(), $notesRepository);
                $json =[
                        'Matiere' => $note->getClasse()->getName(),
                        'Coefficient' => $note->getNotes()->getCoefMoyenne(),
                        'Moyenne' => $note->getNotes()->getNote(),
                        'Moyenne notes classe' => number_format($moy_class, 2, '.', ''),
                        'Min notes classe' =>  number_format($min_class, 2, '.', ''),
                        'Max notes classe' =>  number_format($max_class, 2, '.', ''),
                        'Commentaire' => $note->getNotes()->getProfessorCommentaire()
                ];
                array_push($jsonBulletin, $json);
            }
            return $jsonBulletin;

        }


        public function getJsonBulletinByTrimestre(int $id, StudentNotesService $studentNotesService, ClassStatisticsService $classStatisticsService, ClasseRepository $classeRepository, UserRepository $userRepository, notesRepository $notesRepository, $notes): array
        {
            $noteDetails = $studentNotesService->getNotesDetailsByTrimestre($classeRepository, $userRepository,$id, $notes);

            $jsonBulletin = [];

            foreach($noteDetails as $note)
            {
                $max_class = $classStatisticsService->getMaxNoteInClass($note->getClasse()->getId(), $notesRepository);
                $min_class = $classStatisticsService->getMinNoteInClass($note->getClasse()->getId(), $notesRepository);
                $moy_class = $classStatisticsService->getMoyNoteInClass($note->getClasse()->getId(), $notesRepository);
                $json =[
                        'Matiere' => $note->getClasse()->getName(),
                        'Coefficient' => $note->getNotes()->getCoefMoyenne(),
                        'Moyenne' => $note->getNotes()->getNote(),
                        'Moyenne notes classe' => number_format($moy_class, 2, '.', ''),
                        'Min notes classe' =>  number_format($min_class, 2, '.', ''),
                        'Max notes classe' =>  number_format($max_class, 2, '.', ''),
                        'Commentaire' => $note->getNotes()->getProfessorCommentaire()
                ];
                array_push($jsonBulletin, $json);
            }
            return $jsonBulletin;

        }
}