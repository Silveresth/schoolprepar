<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TestRepository::class)]
class Test
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le titre est obligatoire')]
    #[Assert\Length(min: 5, max: 255, minMessage: 'Minimum {{ limit }} caractères')]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La description est obligatoire')]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: 'La date est obligatoire')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive(message: 'La durée doit être un nombre positif')]
    private ?int $duree = null;

    #[ORM\OneToMany(mappedBy: 'test', targetEntity: Question::class, orphanRemoval: true)]
    private Collection $questions;

    #[ORM\OneToMany(mappedBy: 'test', targetEntity: ResultatTest::class, orphanRemoval: true)]
    private Collection $resultats;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->resultats = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }

    public function getDate(): ?\DateTimeInterface { return $this->date; }
    public function setDate(\DateTimeInterface $date): static { $this->date = $date; return $this; }

    public function getDuree(): ?int { return $this->duree; }
    public function setDuree(?int $duree): static { $this->duree = $duree; return $this; }

    public function getQuestions(): Collection { return $this->questions; }
    public function getResultats(): Collection { return $this->resultats; }

    public function __toString(): string { return $this->titre ?? ''; }
}
