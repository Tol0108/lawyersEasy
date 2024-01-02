<?php

namespace App\Repository;

use App\Entity\GuideJuridique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GuideJuridique>
 *
 * @method GuideJuridique|null find($id, $lockMode = null, $lockVersion = null)
 * @method GuideJuridique|null findOneBy(array $criteria, array $orderBy = null)
 * @method GuideJuridique[]    findAll()
 * @method GuideJuridique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuideJuridiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuideJuridique::class);
    }

//    /**
//     * @return GuideJuridique[] Returns an array of GuideJuridique objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GuideJuridique
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
