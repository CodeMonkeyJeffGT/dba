<?php

namespace App\Repository;

use App\Entity\Exam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Exam|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exam|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exam[]    findAll()
 * @method Exam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Exam::class);
    }

    public function getTableData($name, $address, $teacher)
    {
        return array(
            array(
                'key' => 1,
                'id' => array(23),
                'name' => array('数学'),
                'time' => array('2018/05.12 13:30-14:30'),
                'address' => array('丹青912'),
                'teacher' => array('罗嗣卿', '李彦宏'),
            ),
            array(
                'key' => 2,
                'id' => array(24),
                'name' => array('erp'),
                'time' => array('2018/05.12 13:30-14:30'),
                'address' => array('丹青910'),
                'teacher' => array('李莉', '刘强东'),
            ),
        );
    }

//    /**
//     * @return Exam[] Returns an array of Exam objects
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
    public function findOneBySomeField($value): ?Exam
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
