<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Mentor::class, inversedBy: 'avis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mentor $mentor = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $auteur = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'La note est obligatoire')]
    #[Assert\Range(min: 1, max: 5, notInRangeMessage: 'La note doit être entre {{ min }} et {{ max }}')]
    private ?float $note = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Le commentaire est obligatoire')]
    #[Assert\Length(min: 10, minMessage: 'Le commentaire doit faire au moins {{ limit }} caractères')]
    private ?string $commentaire = null;

    public function getId(): ?int { return $this->id; }

    public function getMentor(): ?Mentor { return $this->mentor; }
    public function setMentor(?Mentor $mentor): static { $this->mentor = $mentor; return $this; }

    public function getAuteur(): ?Utilisateur { return $this->auteur; }
    public function setAuteur(?Utilisateur $auteur): static { $this->auteur = $auteur; return $this; }

    public function getNote(): ?float { return $this->note; }
    public function setNote(float $note): static { $this->note = $note; return $this; }

    public function getCommentaire(): ?string { return $this->commentaire; }
    public function setCommentaire(string $commentaire): static { $this->commentaire = $commentaire; return $this; }
}
