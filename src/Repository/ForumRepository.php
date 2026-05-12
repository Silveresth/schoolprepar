<?php

namespace App\Repository;

use App\Entity\Forum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ForumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Forum::class);
    }

    public function findByFiliereName(string $filiere): array
    {
        return $this->createQueryBuilder('f')
            ->join('f.filiere', 'fi')
            ->where('fi.nom = :nom')
            ->setParameter('nom', $filiere)
            ->orderBy('f.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
