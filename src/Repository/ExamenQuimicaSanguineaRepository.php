<?php

namespace App\Repository;

use App\Entity\ExamenQuimicaSanguinea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExamenQuimicaSanguinea|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExamenQuimicaSanguinea|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExamenQuimicaSanguinea[]    findAll()
 * @method ExamenQuimicaSanguinea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamenQuimicaSanguineaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExamenQuimicaSanguinea::class);
    }

    // /**
    //  * @return ExamenQuimicaSanguinea[] Returns an array of ExamenQuimicaSanguinea objects
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
    public function findOneBySomeField($value): ?ExamenQuimicaSanguinea
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
