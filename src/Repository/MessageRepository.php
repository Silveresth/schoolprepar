<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * Récupère les messages reçus par un utilisateur donné.
     */
    public function findByDestinataire(Utilisateur $user, int $limit = 10): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.destinataire = :user')
            ->setParameter('user', $user)
            ->orderBy('m.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère la conversation entre deux utilisateurs.
     */
    public function findConversation(Utilisateur $user1, Utilisateur $user2): array
    {
        return $this->createQueryBuilder('m')
            ->where('(m.expediteur = :u1 AND m.destinataire = :u2) OR (m.expediteur = :u2 AND m.destinataire = :u1)')
            ->setParameter('u1', $user1)
            ->setParameter('u2', $user2)
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
