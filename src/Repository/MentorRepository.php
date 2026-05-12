<?php

namespace App\Repository;

use App\Entity\Mentor;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MentorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mentor::class);
    }

    /**
     * Trouve le profil mentor lié à un utilisateur donné.
     */
    public function findByUtilisateur(Utilisateur $user): ?Mentor
    {
        return $this->createQueryBuilder('m')
            ->where('m.utilisateur = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
