<?php

// src/Service/MessageGenerator.php
namespace App\Service;

use App\Repository\StudentRepository;
use App\Repository\ClasseRepository;
use App\Repository\NotesRepository;
use App\Entity\Student;

class ClassStatisticsService
{

    public function getMaxNoteInClass(int $class_id, NotesRepository $notesRepository): ?float
    {
           return $notesRepository->createQueryBuilder('n')
                           ->andWhere('n.class_id = :val')
                           ->setParameter('val', $class_id)
                           ->select('MAX(n.note) as max_note')
                           ->getQuery()
                           ->getSingleScalarResult()
                           ;
    }

    public function getStudentsWithMaxNoteNoteInClass(int $class_id, NotesRepository $notesRepository, StudentRepository $studentRepository): ?array
    {
           $student = [];
           $notes = $notesRepository->findStudentByNote($class_id, $this->getMaxNoteInClass($class_id, $notesRepository));
           foreach($notes as $note)
           {
                array_push($student, $studentRepository->findByOneStudentId($note->getStudentId()));
           }
           return $student;
    }

    public function getMinNoteInClass(int $class_id, NotesRepository $notesRepository): ?float
    {
           return $notesRepository->createQueryBuilder('n')
                           ->andWhere('n.class_id = :val')
                           ->setParameter('val', $class_id)
                           ->select('MIN(n.note) as max_note')
                           ->getQuery()
                           ->getSingleScalarResult()
                           ;
    }

    public function getStudentsWithMinNoteNoteInClass(int $class_id, NotesRepository $notesRepository, StudentRepository $studentRepository): ?array
    {
           $student = [];
           $notes = $notesRepository->findStudentByNote($class_id, $this->getMinNoteInClass($class_id, $notesRepository));
           foreach($notes as $note)
           {
                array_push($student, $studentRepository->findByOneStudentId($note->getStudentId()));
           }
           return $student;
    }

    public function getMoyNoteInClass(int $class_id, NotesRepository $notesRepository): ?float
    {
           return $notesRepository->createQueryBuilder('n')
                           ->andWhere('n.class_id = :val')
                           ->setParameter('val', $class_id)
                           ->select('AVG(n.note) as max_note')
                           ->getQuery()
                           ->getSingleScalarResult()
                           ;
    }

    public function getNbrEleveByClassId(int $class_id, NotesRepository $notesRepository)
    {
        return $notesRepository->createQueryBuilder('n')
                           ->andWhere('n.class_id = :val')
                           ->setParameter('val', $class_id)
                           ->select('COUNT(n.note) as max_note')
                           ->getQuery()
                           ->getSingleScalarResult()
                           ;
    }

    public function getNbrStudentSucceed(int $class_id, NotesRepository $notesRepository, ?float $seuil): ?float
    {
           return $notesRepository->createQueryBuilder('n')
                           ->andWhere('n.class_id = :val')
                           ->andWhere('n.note >= :seuil')
                           ->setParameter('val', $class_id)
                           ->setParameter('seuil', $seuil)
                           ->select('COUNT(n.note) as max_note')
                           ->getQuery()
                           ->getSingleScalarResult()
                           ;
    }

    public function getPercStudentSucceed(int $class_id, NotesRepository $notesRepository, ?float $seuil): ?int
    {
           $notes = $this->getNbrEleveByClassId($class_id, $notesRepository);
           if($notes==0){
                $notes =1;
           }
           return ($this->getNbrStudentSucceed($class_id, $notesRepository,$seuil)/$notes)*100;
    }

    public function getNbrStudentFailed(int $class_id, NotesRepository $notesRepository, ?float $seuil): ?int
    {
           return $this->getNbrEleveByClassId($class_id, $notesRepository) - $this->getNbrStudentSucceed($class_id, $notesRepository,$seuil);
    }

    public function getNbrStudentsAbsent(int $class_id, NotesRepository $notesRepository): ?float
    {
           return $notesRepository->createQueryBuilder('n')
                           ->andWhere('n.class_id = :val')
                           ->andWhere('n.nbr_absences >0')
                           ->setParameter('val', $class_id)
                           ->select('COUNT(n.note) as max_note')
                           ->getQuery()
                           ->getSingleScalarResult()
                           ;
    }

    public function getMaxNbrStudentsAbsent(int $class_id, NotesRepository $notesRepository): ?float
    {
           return $notesRepository->createQueryBuilder('n')
                           ->andWhere('n.class_id = :val')
                           ->andWhere('n.nbr_absences >0')
                           ->setParameter('val', $class_id)
                           ->select('MAX(n.nbr_absences) as max_note')
                           ->getQuery()
                           ->getSingleScalarResult()
                           ;
    }

    public function getStudentWithMaxAbsence(int $class_id, NotesRepository $notesRepository, StudentRepository $studentRepository): ?array
    {
            $students = [];
            $notes = $notesRepository->findStudentByAbsence($class_id, $this->getMaxNbrStudentsAbsent($class_id, $notesRepository));
            foreach($notes as $note)
            {
                 array_push($students, $studentRepository->findByOneStudentId($note->getStudentId()));
            }
            return $students;
    }

}