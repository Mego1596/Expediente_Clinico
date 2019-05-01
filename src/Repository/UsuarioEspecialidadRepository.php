<?php

namespace App\Repository;

use App\Entity\UsuarioEspecialidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UsuarioEspecialidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsuarioEspecialidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsuarioEspecialidad[]    findAll()
 * @method UsuarioEspecialidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioEspecialidadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UsuarioEspecialidad::class);
    }

    // /**
    //  * @return UsuarioEspecialidad[] Returns an array of UsuarioEspecialidad objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UsuarioEspecialidad
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
