<?php

namespace App\Repository;

use App\Entity\ExamenSolicitado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExamenSolicitado|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExamenSolicitado|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExamenSolicitado[]    findAll()
 * @method ExamenSolicitado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamenSolicitadoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExamenSolicitado::class);
    }

    // /**
    //  * @return ExamenSolicitado[] Returns an array of ExamenSolicitado objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExamenSolicitado
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
