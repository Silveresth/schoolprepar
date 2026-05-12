<?php

namespace App\Entity;

use App\Repository\MentorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MentorRepository::class)]
class Mentor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La biographie est obligatoire')]
    #[Assert\Length(min: 50, minMessage: 'La bio doit faire au moins {{ limit }} caractères')]
    private ?string $bio = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La spécialité est obligatoire')]
    private ?string $specialite = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $experience = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Le tarif est obligatoire')]
    #[Assert\Positive(message: 'Le tarif doit être un nombre positif')]
    private ?float $tarif = null;

    #[ORM\OneToMany(mappedBy: 'mentor', targetEntity: Avis::class, orphanRemoval: true)]
    private Collection $avis;

    #[ORM\OneToMany(mappedBy: 'idMentor', targetEntity: RendezVous::class, orphanRemoval: true)]
    private Collection $rendezVous;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
        $this->rendezVous = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getUtilisateur(): ?Utilisateur { return $this->utilisateur; }
    public function setUtilisateur(?Utilisateur $utilisateur): static { $this->utilisateur = $utilisateur; return $this; }

    public function getBio(): ?string { return $this->bio; }
    public function setBio(string $bio): static { $this->bio = $bio; return $this; }

    public function getSpecialite(): ?string { return $this->specialite; }
    public function setSpecialite(string $specialite): static { $this->specialite = $specialite; return $this; }

    public function getExperience(): ?string { return $this->experience; }
    public function setExperience(?string $experience): static { $this->experience = $experience; return $this; }

    public function getTarif(): ?float { return $this->tarif; }
    public function setTarif(float $tarif): static { $this->tarif = $tarif; return $this; }

    public function getAvis(): Collection { return $this->avis; }
    public function getRendezVous(): Collection { return $this->rendezVous; }

    public function __toString(): string
    {
        return $this->utilisateur ? $this->utilisateur->getNomComplet() : '';
    }
}
