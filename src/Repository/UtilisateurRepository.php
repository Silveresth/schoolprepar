<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    /**
     * Retourne tous les utilisateurs ayant un rôle conseiller ou mentor.
     */
    public function findConseillers(): array
    {
        return $this->createQueryBuilder('u')
            ->where("u.role IN ('ROLE_CONSEILLER', 'ROLE_MENTOR')")
            ->orderBy('u.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
