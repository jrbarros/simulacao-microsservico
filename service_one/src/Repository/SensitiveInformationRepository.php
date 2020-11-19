<?php

namespace App\Repository;

use App\Entity\SensitiveInformation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SensitiveInformation|null find($id, $lockMode = null, $lockVersion = null)
 * @method SensitiveInformation|null findOneBy(array $criteria, array $orderBy = null)
 * @method SensitiveInformation[]    findAll()
 * @method SensitiveInformation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SensitiveInformationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SensitiveInformation::class);
    }

    // /**
    //  * @return SensitiveInformation[] Returns an array of SensitiveInformation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SensitiveInformation
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
