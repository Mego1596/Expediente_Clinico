<?php

namespace App\Repository;

use App\Entity\Familiar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Familiar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Familiar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Familiar[]    findAll()
 * @method Familiar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FamiliarRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Familiar::class);
    }

    // /**
    //  * @return Familiar[] Returns an array of Familiar objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Familiar
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
