<?php

namespace App\Repository;

use App\Entity\Illustration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Illustration>
 */
class IllustrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Illustration::class);
    }

    /**
     * @return Illustration[] Returns an array of all illustrations
     */
    public function findAll(): array
    {
        return $this->findBy([], ['trick' => 'ASC']);
    }

    /**
     * @return Illustration[] Returns an array of Illustration objects
     */
    public function findByTrickId($trickId): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.trick = :trickId')
            ->setParameter('trickId', $trickId)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $trickId
     * @return Illustration|null
     */
//    public function findOneByTrickId($trickId): ?Illustration
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.trick = :trickId')
//            ->setParameter('trickId', $trickId)
//            ->getQuery()
//            ->getOneOrNullResult();
//    }

    /**
     * @param int $trickId
     * @return Illustration|null
     */
    public function findFirstByTrickId(int $trickId): ?Illustration
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.trick = :trickId')
            ->setParameter('trickId', $trickId)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
