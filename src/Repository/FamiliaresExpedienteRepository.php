<?php

namespace App\Repository;

use App\Entity\FamiliaresExpediente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FamiliaresExpediente|null find($id, $lockMode = null, $lockVersion = null)
 * @method FamiliaresExpediente|null findOneBy(array $criteria, array $orderBy = null)
 * @method FamiliaresExpediente[]    findAll()
 * @method FamiliaresExpediente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FamiliaresExpedienteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FamiliaresExpediente::class);
    }

    // /**
    //  * @return FamiliaresExpediente[] Returns an array of FamiliaresExpediente objects
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
    public function findOneBySomeField($value): ?FamiliaresExpediente
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
