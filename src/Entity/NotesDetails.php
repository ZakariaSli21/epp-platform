<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use App\Entity\Classe;
use App\Entity\Notes;

class NotesDetails
{
    private ?Classe $classe = null;

    private ?Notes $notes = null;

    private ?string $professor_name = null;


    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(Classe $classe): self
    {
        $this->classe = $classe;

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

    public function getProfessorName(): ?string
    {
        return $this->professor_name;
    }

    public function setProfessorName(string $professor_name): self
    {
        $this->professor_name = $professor_name;

        return $this;
    }

    function __construct()
    {
    }
}
