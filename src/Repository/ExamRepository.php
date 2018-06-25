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

    public function getTableData($name, $address, $teacher, $status)
    {
        $conn = $this->getEntityManager()->getConnection();
        $nameLike = $this->toLike($name);
        $addressLike = $this->toLike($address);
        $teacherLike = $this->toLike($teacher);
        $sqlStatus = '';
        switch ($status) {
            case 'unassigned':

                break;
            case 'assigned':
                break;
            case 'completed':
                $sqlStatus = '
                    AND `t`.`start` > ' . date('Y-m-d H:i:s', time()) . '
                ';
                break;
        }
        $sql = 'SELECT `e`.*, `t`.`name` `teacher`, `t`.`id` `t_id`
        FROM `exam` `e`
        LEFT JOIN `exam_teacher` `et` ON `e`.`id` = `et`.`e_id`
        LEFT JOIN `teacher` `t` ON `et`.`t_id` = `t`.`id`
        WHERE `e`.`id` IN (
            SELECT `e1`.`id` `id`
            FROM `exam` `e1`
            LEFT JOIN `exam_teacher` `et1` ON `e1`.`id` = `et1`.`e_id`
            LEFT JOIN `teacher` `t1` ON `et1`.`t_id` = `t1`.`id`
            WHERE `e1`.`name` LIKE :name
            AND `e1`.`address` LIKE :address
            AND (
                `t1`.`name` LIKE :teacher
                OR `t1`.`name` IS NULL
            )
            UNION
            SELECT `e2`.`id` `id`
            FROM `exam` `e2`
            LEFT JOIN `exam_teacher` `et2` ON `e2`.`id` = `et2`.`e_id`
            LEFT JOIN `teacher` `t2` ON `et2`.`t_id` = `t2`.`id`
            WHERE `e2`.`name` LIKE :name_like
            AND `e2`.`address` LIKE :address_like
            AND (
                `t2`.`name` LIKE :teacher_like
                OR `t2`.`name` IS NULL
            )
        )
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'name' => '%' . $name . '%',
            'address' => '%' . $address . '%',
            'teacher' => '%' . $teacher . '%',
            'name_like' => $nameLike,
            'address_like' => $addressLike,
            'teacher_like' => $teacherLike,
        ));

        $rst = $stmt->fetchAll();
        $rst = $this->mergeTable($rst);
        return $rst;
    }
    
    public function editExam($id, $subject, $start, $end, $address, $teacher, $confirm)
    {

    }

    public function deleteExam($id)
    {
        
    }

    public function remindExam($id)
    {

    }

    public function insExam($subject, $start, $end, $address, $teacher, $confirm = false)
    {
        if ($this->checkRepeat($start, $end, $teacher) && ! $confirm) {
            return array(
                'type' => 'confirm',
                'msg' => $this->msg,
            );
        }
        return true;
    }

    private function checkRepeat($start, $end, $teacher)
    {
        $conn = $this->getEntityManager()->getConnection();
        // $sql = 'SELECT `e`.`name`
        //     FROM `exam` `e`
        //     WHERE (
        //         `e`.`start` < :start
        //         AND `e`.`start` > :end
        //     ) OR (
        //         `e`.`end` > :start
        //         AND `e`.`end` < :end
        //     )
        // ';
        // $stmt = $conn->prepare($sql);
        // $stmt->execute(array(
        // ));
        return true;
    }

    private function mergeTable($arr): array
    {
        $rst = array();
        for ($i = 0, $loop = count($arr); $i < $loop; $i++) {
            if ( ! isset($rst[$arr[$i]['id']])) {
                $rst[$arr[$i]['id']] = array(
                    'key' => (int)$arr[$i]['id'],
                    'id' => (int)$arr[$i]['id'],
                    'subject' => $arr[$i]['name'],
                    'start' => $arr[$i]['start'],
                    'end' => $arr[$i]['end'],
                    'address' => $arr[$i]['address'],
                    'teacher' => array($arr[$i]['teacher'] . '-' . $arr[$i]['t_id']),
                );
            } else {
                $rst[$arr[$i]['id']]['teacher'][] = $arr[$i]['teacher'] . '-' . $arr[$i]['t_id'];
            }
        }
        return array_values($rst);
    }

    private function toLike($str): string
    {
        for ($i = 0, $loop = mb_strlen($str); $i < $loop; $i++) {
            $str = mb_substr($str, 0, $i * 2) . '%' . mb_substr($str, $i * 2, $i + $loop);
        }
        return $str . '%';
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
