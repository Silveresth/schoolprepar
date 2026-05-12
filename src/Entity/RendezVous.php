<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $idEleve = null;

    #[ORM\ManyToOne(targetEntity: Mentor::class, inversedBy: 'rendezVous')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mentor $idMentor = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: 'La date est obligatoire')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Le statut est obligatoire')]
    #[Assert\Choice(choices: ['en_attente', 'confirme', 'annule'], message: 'Statut invalide')]
    private string $statut = 'en_attente';

    public function getId(): ?int { return $this->id; }

    public function getIdEleve(): ?Utilisateur { return $this->idEleve; }
    public function setIdEleve(?Utilisateur $idEleve): static { $this->idEleve = $idEleve; return $this; }

    public function getIdMentor(): ?Mentor { return $this->idMentor; }
    public function setIdMentor(?Mentor $idMentor): static { $this->idMentor = $idMentor; return $this; }

    public function getDate(): ?\DateTimeInterface { return $this->date; }
    public function setDate(\DateTimeInterface $date): static { $this->date = $date; return $this; }

    public function getStatut(): string { return $this->statut; }
    public function setStatut(string $statut): static { $this->statut = $statut; return $this; }
}
