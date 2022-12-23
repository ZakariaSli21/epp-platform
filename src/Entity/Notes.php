<?php

namespace App\Entity;

use App\Repository\NotesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotesRepository::class)]
class Notes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $student_id = null;

    #[ORM\Column(nullable: true)]
    private ?float $note = null;

    #[ORM\Column(nullable: true)]
    private ?float $nbr_absences = null;

    #[ORM\Column]
    private ?int $class_id = null;

    #[ORM\Column(nullable: true)]
    private ?float $coef_absences = null;

    #[ORM\Column(nullable: true)]
    private ?float $coef_moyenne = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $professor_commentaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudentId(): ?int
    {
        return $this->student_id;
    }

    public function setStudentId(int $student_id): self
    {
        $this->student_id = $student_id;

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getNbrAbsences(): ?float
    {
        return $this->nbr_absences;
    }

    public function setNbrAbsences(?float $nbr_absences): self
    {
        $this->nbr_absences = $nbr_absences;

        return $this;
    }

    public function getClassId(): ?int
    {
        return $this->class_id;
    }

    public function setClassId(int $class_id): self
    {
        $this->class_id = $class_id;

        return $this;
    }

    public function getCoefAbsences(): ?float
    {
        return $this->coef_absences;
    }

    public function setCoefAbsences(?float $coef_absences): self
    {
        $this->coef_absences = $coef_absences;

        return $this;
    }

    public function getCoefMoyenne(): ?float
    {
        return $this->coef_moyenne;
    }

    public function setCoefMoyenne(?float $coef_moyenne): self
    {
        $this->coef_moyenne = $coef_moyenne;

        return $this;
    }

    public function getProfessorCommentaire(): ?string
    {
        return $this->professor_commentaire;
    }

    public function setProfessorCommentaire(?string $professor_commentaire): self
    {
        $this->professor_commentaire = $professor_commentaire;

        return $this;
    }
}
