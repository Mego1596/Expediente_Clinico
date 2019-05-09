<?php

namespace App\Repository;

use App\Entity\Anexo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Anexo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Anexo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Anexo[]    findAll()
 * @method Anexo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnexoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Anexo::class);
    }

    // /**
    //  * @return Anexo[] Returns an array of Anexo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Anexo
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
