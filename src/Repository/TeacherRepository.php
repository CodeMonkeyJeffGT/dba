<?php

namespace App\Repository;

use App\Entity\Teacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Teacher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Teacher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Teacher[]    findAll()
 * @method Teacher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeacherRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Teacher::class);
    }

    public function getMultselect($search): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $searchLike = $this->toLike($search);
        $sql = 'SELECT `t1`.`id` `id`, `t1`.`name` `name`
        FROM `teacher` `t1`
        WHERE `t1`.`name` LIKE :search
        UNION
        SELECT `t2`.`id` `id`, `t2`.`name` `name`
        FROM `teacher` `t2`
        WHERE `t2`.`name` LIKE :search_like
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'search' => '%' . $search . '%',
            'search_like' => $searchLike,
        ));

        $rst = $stmt->fetchAll();
        return $rst;
    }

    public function getTableData($search): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $searchLike = $this->toLike($search);
        $sql = 'SELECT `t1`.`id` `id`, `t1`.`name` `name`, `t1`.`account` `account`, `t1`.`phone` `phone`
        FROM `teacher` `t1`
        WHERE `t1`.`name` LIKE :search
        UNION
        SELECT `t2`.`id` `id`, `t2`.`name` `name`, `t2`.`account` `account`, `t2`.`phone` `phone`
        FROM `teacher` `t2`
        WHERE `t2`.`name` LIKE :search_like
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'search' => '%' . $search . '%',
            'search_like' => $searchLike,
        ));

        $rst = $stmt->fetchAll();
        return $this->mergeTable($rst);
    }

    public function editTeacher($id, $name, $phone)
    {

    }

    public function deleteTeacher($id)
    {

    }

    public function permitTeacher($id)
    {

    }

    public function newTeacher($name, $account, $password, $phone)
    {

    }

    private function mergeTable($arr): array
    {
        $rst = array();
        for ($i = 0, $loop = count($arr); $i < $loop; $i++) {
            $rst[] = array(
                'key' => (int)$arr[$i]['id'],
                'id' => (int)$arr[$i]['id'],
                'name' => $arr[$i]['name'],
                'account' => $arr[$i]['account'],
                'phone' => $arr[$i]['phone'],
            );
        }
        return $rst;
    }

    private function toLike($str): string
    {
        for ($i = 0, $loop = mb_strlen($str); $i < $loop; $i++) {
            $str = mb_substr($str, 0, $i * 2) . '%' . mb_substr($str, $i * 2, $i + $loop);
        }
        return $str . '%';
    }

//    /**
//     * @return Teacher[] Returns an array of Teacher objects
//     */
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
    public function findOneBySomeField($value): ?Teacher
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
