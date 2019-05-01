<?php

namespace App\Repository;

use App\Entity\TipoHabitacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TipoHabitacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoHabitacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoHabitacion[]    findAll()
 * @method TipoHabitacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoHabitacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TipoHabitacion::class);
    }

    // /**
    //  * @return TipoHabitacion[] Returns an array of TipoHabitacion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TipoHabitacion
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
