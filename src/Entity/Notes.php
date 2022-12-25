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

    #[ORM\Column]
    private ?int $class_id = null;

    #[ORM\Column(nullable: true)]
    private ?float $note = null;

    #[ORM\Column(nullable: true)]
    private ?float $nbr_absences = null;

    #[ORM\Column(nullable: true)]
    private ?float $note_moyenne = null;

    #[ORM\Column(nullable: true)]
    private ?float $coef_moyenne = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $professor_commentaire = null;

    #[ORM\Column(nullable: true)]
    private ?float $note1 = null;

    #[ORM\Column(nullable: true)]
    private ?float $coef1 = null;

    #[ORM\Column(nullable: true)]
    private ?float $note2 = null;

    #[ORM\Column(nullable: true)]
    private ?float $coef2 = null;

    #[ORM\Column(nullable: true)]
    private ?float $note3 = null;

    #[ORM\Column(nullable: true)]
    private ?float $coef3 = null;

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

    public function setNoteAutomaticaly(): self
    {
         $nume = $this->note1 * $this->coef1 + $this->note2 * $this->coef2 + $this->note3 * $this->coef3;
         $denum = $this->coef1 + $this->coef2 + $this->coef3;
         if($denum==0){
            $denum=1;
         }
         $this->note = $nume/$denum;
         return $this;
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

    public function getNoteMoyenne(): ?float
    {
        return $this->note_moyenne;
    }

    public function setNoteMoyenne(?float $note_moyenne): self
    {
        $this->note_moyenne = $note_moyenne;

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

    public function getNote1(): ?float
    {
        return $this->note1;
    }

    public function setNote1(?float $note1): self
    {
        $this->note1 = $note1;

        return $this;
    }

    public function getCoef1(): ?float
    {
        return $this->coef1;
    }

    public function setCoef1(?float $coef1): self
    {
        $this->coef1 = $coef1;

        return $this;
    }

    public function getNote2(): ?float
    {
        return $this->note2;
    }

    public function setNote2(?float $note2): self
    {
        $this->note2 = $note2;

        return $this;
    }

    public function getCoef2(): ?float
    {
        return $this->coef2;
    }

    public function setCoef2(?float $coef2): self
    {
        $this->coef2 = $coef2;

        return $this;
    }

    public function getNote3(): ?float
    {
        return $this->note3;
    }

    public function setNote3(?float $note3): self
    {
        $this->note3 = $note3;

        return $this;
    }

    public function getCoef3(): ?float
    {
        return $this->coef3;
    }

    public function setCoef3(?float $coef3): self
    {
        $this->coef3 = $coef3;

        return $this;
    }
}
