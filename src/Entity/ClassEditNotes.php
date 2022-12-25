<?php

namespace App\Entity;


class ClassEditNotes
{
    private ?int $studentid = null;

    private ?float $note1 = null;

    private ?float $coef1 = null;

    private ?float $note2 = null;

    private ?float $coef2 = null;

    private ?float $note3 = null;

    private ?float $coef3 = null;

    private ?float $absence = null;

    private ?float $note = null;

    private ?float $notecoef = null;

    private ?string $commentaire = null;

    public function getStudentid(): ?int
    {
        return $this->studentid;
    }

    public function setStudentid(int $studentid): self
    {
        $this->studentid = $studentid;

        return $this;
    }

    public function getNote1(): ?float
    {
        return $this->note1;
    }

    public function setNote1(float $note1): self
    {
        $this->note1 = $note1;

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getCoef1(): ?float
    {
        return $this->coef1;
    }

    public function setCoef1(float $coef1): self
    {
        $this->coef1 = $coef1;

        return $this;
    }

    public function getNote2(): ?float
    {
        return $this->note2;
    }

    public function setNote2(float $note2): self
    {
        $this->note2 = $note2;

        return $this;
    }

    public function getCoef2(): ?float
    {
        return $this->coef2;
    }

    public function setCoef2(float $coef2): self
    {
        $this->coef2 = $coef2;

        return $this;
    }

    public function getNote3(): ?float
    {
        return $this->note3;
    }

    public function setNote3(float $note3): self
    {
        $this->note3 = $note3;

        return $this;
    }

    public function getCoef3(): ?float
    {
        return $this->coef3;
    }

    public function setCoef3(float $coef3): self
    {
        $this->coef3 = $coef3;

        return $this;
    }

    public function getAbsence(): ?float
    {
        return $this->absence;
    }

    public function setAbsence(float $absence): self
    {
        $this->absence = $absence;

        return $this;
    }

    public function getNotecoef(): ?float
    {
        return $this->notecoef;
    }

    public function setNotecoef(float $notecoef): self
    {
        $this->notecoef = $notecoef;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    function __construct()
    {
    }
}
