<?php

namespace App\Entity;

use App\Repository\ForumReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ForumReponseRepository::class)]
class ForumReponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Forum::class, inversedBy: 'reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Forum $forum = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $auteur = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La réponse ne peut pas être vide')]
    #[Assert\Length(min: 2, minMessage: 'Minimum {{ limit }} caractères')]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }

    public function getForum(): ?Forum { return $this->forum; }
    public function setForum(?Forum $forum): static { $this->forum = $forum; return $this; }

    public function getAuteur(): ?Utilisateur { return $this->auteur; }
    public function setAuteur(?Utilisateur $auteur): static { $this->auteur = $auteur; return $this; }

    public function getContenu(): ?string { return $this->contenu; }
    public function setContenu(string $contenu): static { $this->contenu = $contenu; return $this; }

    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $createdAt): static { $this->createdAt = $createdAt; return $this; }
}
