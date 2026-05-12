<?php

namespace App\Repository;

use App\Entity\Mentor;
use App\Entity\RendezVous;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RendezVousRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RendezVous::class);
    }

    /**
     * Trouve les rendez-vous d'un mentor donné, triés par date.
     */
    public function findByMentor(Mentor $mentor, int $limit = 20): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.idMentor = :mentor')
            ->setParameter('mentor', $mentor)
            ->orderBy('r.date', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les rendez-vous d'un élève donné.
     */
    public function findByEleve(Utilisateur $user): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.idEleve = :user')
            ->setParameter('user', $user)
            ->orderBy('r.date', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
