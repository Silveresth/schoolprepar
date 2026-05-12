<?php

namespace App\Entity;

use App\Repository\ForumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ForumRepository::class)]
class Forum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Filiere::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Filiere $filiere = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $auteur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le titre est obligatoire')]
    #[Assert\Length(min: 5, max: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Le contenu est obligatoire')]
    #[Assert\Length(min: 10)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'forum', targetEntity: ForumReponse::class, orphanRemoval: true)]
    #[ORM\OrderBy(['createdAt' => 'ASC'])]
    private Collection $reponses;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->reponses = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getFiliere(): ?Filiere { return $this->filiere; }
    public function setFiliere(?Filiere $filiere): static { $this->filiere = $filiere; return $this; }

    public function getAuteur(): ?Utilisateur { return $this->auteur; }
    public function setAuteur(?Utilisateur $auteur): static { $this->auteur = $auteur; return $this; }

    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }

    public function getContenu(): ?string { return $this->contenu; }
    public function setContenu(string $contenu): static { $this->contenu = $contenu; return $this; }

    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $d): static { $this->createdAt = $d; return $this; }

    public function getReponses(): Collection { return $this->reponses; }
    public function addReponse(ForumReponse $r): static {
        if (!$this->reponses->contains($r)) { $this->reponses->add($r); $r->setForum($this); }
        return $this;
    }
    public function removeReponse(ForumReponse $r): static {
        if ($this->reponses->removeElement($r)) { if ($r->getForum() === $this) $r->setForum(null); }
        return $this;
    }
}
