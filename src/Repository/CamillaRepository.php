<?php

namespace App\Repository;

use App\Entity\Camilla;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Camilla|null find($id, $lockMode = null, $lockVersion = null)
 * @method Camilla|null findOneBy(array $criteria, array $orderBy = null)
 * @method Camilla[]    findAll()
 * @method Camilla[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CamillaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Camilla::class);
    }

    // /**
    //  * @return Camilla[] Returns an array of Camilla objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Camilla
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
