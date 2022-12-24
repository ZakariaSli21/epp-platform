<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use App\Entity\Student;
use App\Entity\Notes;

class ClassDetails
{
    private ?Student $student = null;

    private ?Notes $notes = null;


    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getNotes(): ?Notes
    {
        return $this->notes;
    }

    public function setNotes(Notes $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    function __construct()
    {
    }
}
