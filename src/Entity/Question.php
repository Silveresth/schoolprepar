<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Test::class, inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Test $test = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Le contenu de la question est obligatoire')]
    #[Assert\Length(min: 5, minMessage: 'La question doit faire au moins {{ limit }} caractères')]
    private ?string $contenu = null;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Reponse::class, orphanRemoval: true)]
    private Collection $reponses;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getTest(): ?Test { return $this->test; }
    public function setTest(?Test $test): static { $this->test = $test; return $this; }

    public function getContenu(): ?string { return $this->contenu; }
    public function setContenu(string $contenu): static { $this->contenu = $contenu; return $this; }

    public function getReponses(): Collection { return $this->reponses; }
}
