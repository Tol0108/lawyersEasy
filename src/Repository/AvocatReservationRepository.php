<?php

namespace App\Repository;

use App\Entity\AvocatReservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AvocatReservation>
 *
 * @method AvocatReservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvocatReservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvocatReservation[]    findAll()
 * @method AvocatReservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvocatReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvocatReservation::class);
    }

//    /**
//     * @return AvocatReservation[] Returns an array of AvocatReservation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AvocatReservation
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
