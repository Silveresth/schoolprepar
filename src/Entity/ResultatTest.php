<?php

namespace App\Entity;

use App\Repository\ResultatTestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ResultatTestRepository::class)]
class ResultatTest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $user = null;

    #[ORM\ManyToOne(targetEntity: Test::class, inversedBy: 'resultats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Test $test = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Le score est obligatoire')]
    #[Assert\Range(min: 0, max: 100, notInRangeMessage: 'Le score doit être entre {{ min }} et {{ max }}')]
    private ?float $score = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int { return $this->id; }

    public function getUser(): ?Utilisateur { return $this->user; }
    public function setUser(?Utilisateur $user): static { $this->user = $user; return $this; }

    public function getTest(): ?Test { return $this->test; }
    public function setTest(?Test $test): static { $this->test = $test; return $this; }

    public function getScore(): ?float { return $this->score; }
    public function setScore(float $score): static { $this->score = $score; return $this; }

    public function getDate(): ?\DateTimeInterface { return $this->date; }
    public function setDate(\DateTimeInterface $date): static { $this->date = $date; return $this; }
}
