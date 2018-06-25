<?php

namespace App\Repository;

use App\Entity\ExamTeacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExamTeacher|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExamTeacher|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExamTeacher[]    findAll()
 * @method ExamTeacher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamTeacherRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExamTeacher::class);
    }

//    /**
//     * @return ExamTeacher[] Returns an array of ExamTeacher objects
//     */
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
    public function findOneBySomeField($value): ?ExamTeacher
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
