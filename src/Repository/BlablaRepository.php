<?php

namespace App\Repository;

use App\Entity\Blabla;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blabla>
 *
 * @method Blabla|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blabla|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blabla[]    findAll()
 * @method Blabla[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlablaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blabla::class);
    }

//    /**
//     * @return Blabla[] Returns an array of Blabla objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Blabla
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
