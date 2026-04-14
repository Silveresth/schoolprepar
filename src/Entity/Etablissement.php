<?php

namespace App\Entity;

use App\Repository\EtablissementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EtablissementRepository::class)]
class Etablissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse est obligatoire")]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La ville est obligatoire')]
    private ?string $ville = null;

    // Relation 1:N avec Filiere (un établissement a plusieurs filières)
    #[ORM\OneToMany(targetEntity: Filiere::class, mappedBy: 'etablissement', orphanRemoval: true)]
    private Collection $filieres;

    // Relation 1:N avec Evenement (un établissement organise plusieurs événements)
    #[ORM\OneToMany(targetEntity: Evenement::class, mappedBy: 'etablissement', orphanRemoval: true)]
    private Collection $evenements;

    public function __construct()
    {
        $this->filieres = new ArrayCollection();
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getAdresse(): ?string { return $this->adresse; }
    public function setAdresse(string $adresse): static { $this->adresse = $adresse; return $this; }

    public function getVille(): ?string { return $this->ville; }
    public function setVille(string $ville): static { $this->ville = $ville; return $this; }

    public function getFilieres(): Collection { return $this->filieres; }
    public function addFiliere(Filiere $filiere): static {
        if (!$this->filieres->contains($filiere)) {
            $this->filieres->add($filiere);
            $filiere->setEtablissement($this);
        }
        return $this;
    }
    public function removeFiliere(Filiere $filiere): static {
        if ($this->filieres->removeElement($filiere)) {
            if ($filiere->getEtablissement() === $this) {
                $filiere->setEtablissement(null);
            }
        }
        return $this;
    }

    public function getEvenements(): Collection { return $this->evenements; }
    public function addEvenement(Evenement $evenement): static {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements->add($evenement);
            $evenement->setEtablissement($this);
        }
        return $this;
    }
    public function removeEvenement(Evenement $evenement): static {
        if ($this->evenements->removeElement($evenement)) {
            if ($evenement->getEtablissement() === $this) {
                $evenement->setEtablissement(null);
            }
        }
        return $this;
    }

    public function __toString(): string { return $this->nom ?? ''; }
}
