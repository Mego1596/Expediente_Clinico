<?php

namespace App\Repository;

use App\Entity\CodigoInternacional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CodigoInternacional|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodigoInternacional|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodigoInternacional[]    findAll()
 * @method CodigoInternacional[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodigoInternacionalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CodigoInternacional::class);
    }

    // /**
    //  * @return CodigoInternacional[] Returns an array of CodigoInternacional objects
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
    public function findOneBySomeField($value): ?CodigoInternacional
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
