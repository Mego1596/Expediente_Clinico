<?php

namespace App\Repository;

use App\Entity\Ingresado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ingresado|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingresado|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingresado[]    findAll()
 * @method Ingresado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngresadoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ingresado::class);
    }

    // /**
    //  * @return Ingresado[] Returns an array of Ingresado objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ingresado
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
