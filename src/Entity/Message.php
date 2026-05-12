<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $expediteur = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $destinataire = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Le message ne peut pas être vide')]
    #[Assert\Length(min: 2, max: 5000, minMessage: 'Minimum {{ limit }} caractères', maxMessage: 'Maximum {{ limit }} caractères')]
    private ?string $contenu = null;

    public function getId(): ?int { return $this->id; }

    public function getExpediteur(): ?Utilisateur { return $this->expediteur; }
    public function setExpediteur(?Utilisateur $expediteur): static { $this->expediteur = $expediteur; return $this; }

    public function getDestinataire(): ?Utilisateur { return $this->destinataire; }
    public function setDestinataire(?Utilisateur $destinataire): static { $this->destinataire = $destinataire; return $this; }

    public function getContenu(): ?string { return $this->contenu; }
    public function setContenu(string $contenu): static { $this->contenu = $contenu; return $this; }
}
