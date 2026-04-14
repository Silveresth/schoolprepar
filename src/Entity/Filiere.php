<?php

namespace App\Entity;

use App\Repository\FiliereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FiliereRepository::class)]
class Filiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La description est obligatoire')]
    private ?string $description = null;

    #[ORM\Column(length: 127)]
    #[Assert\NotBlank(message: 'Le débouché est obligatoire')]
    private ?string $debouche = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le niveau requis est obligatoire')]
    private ?string $niveau_requis = null;

    // Relation N:1 avec Etablissement (1 établissement possède N filières)
    #[ORM\ManyToOne(inversedBy: 'filieres')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Veuillez sélectionner un établissement')]
    private ?Etablissement $etablissement = null;

    // Relation N:N avec Evenement (N filières concernées par N événements)
    #[ORM\ManyToMany(targetEntity: Evenement::class, mappedBy: 'filieres')]
    private Collection $evenements;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }

    public function getDebouche(): ?string { return $this->debouche; }
    public function setDebouche(string $debouche): static { $this->debouche = $debouche; return $this; }

    public function getNiveauRequis(): ?string { return $this->niveau_requis; }
    public function setNiveauRequis(string $niveau_requis): static { $this->niveau_requis = $niveau_requis; return $this; }

    public function getEtablissement(): ?Etablissement { return $this->etablissement; }
    public function setEtablissement(?Etablissement $etablissement): static { $this->etablissement = $etablissement; return $this; }

    public function getEvenements(): Collection { return $this->evenements; }

    public function __toString(): string { return $this->nom ?? ''; }
}
