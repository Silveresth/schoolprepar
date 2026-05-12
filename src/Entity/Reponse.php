<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La réponse ne peut pas être vide')]
    #[Assert\Length(min: 1, max: 1000, maxMessage: 'Maximum {{ limit }} caractères')]
    private ?string $contenu = null;

    // Points accordés pour cette réponse (0 = incorrecte, >0 = correcte)
    #[ORM\Column(nullable: false)]
    #[Assert\NotNull]
    #[Assert\Range(min: 0, max: 100, notInRangeMessage: 'Les points doivent être entre {{ min }} et {{ max }}')]
    private int $points = 0;


    public function getId(): ?int { return $this->id; }

    public function getQuestion(): ?Question { return $this->question; }
    public function setQuestion(?Question $question): static { $this->question = $question; return $this; }

    public function getContenu(): ?string { return $this->contenu; }
    public function setContenu(string $contenu): static { $this->contenu = $contenu; return $this; }

    public function getPoints(): int { return $this->points; }
    public function setPoints(int $points): static { $this->points = $points; return $this; }
}

