<?php

namespace App\Repository;

use App\Entity\Exam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ExamRepository extends ServiceEntityRepository
{
    private $msg;

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
                $sqlStatus = 'AND `t`.`name` IS NULL AND `e`.`start` > "' . date('Y-m-d H:i:s', time()) . '"';
                break;
            case 'assigned':
                $sqlStatus = 'AND `t`.`name` IS NOT NULL AND `e`.`start` > "' . date('Y-m-d H:i:s', time()) . '"';
                break;
            case 'completed':
                $sqlStatus = 'AND `e`.`start` <= "' . date('Y-m-d H:i:s', time()) . '"';
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
        ' . $sqlStatus;
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
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT `e`.`id`
        FROM `exam` `e`
        WHERE `e`.`id` = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'id' => $id,
        ));
        if (count($stmt->fetchAll()) == 0) {
            return array(
                'type' => 'error',
                'msg' => '监考信息不存在',
            );
        }

        $sql = 'SELECT `t_id` `id`
        FROM `exam_teacher`
        WHERE `e_id` = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'id' => $id,
        ));
        $ids = array();
        foreach ($stmt->fetchAll() as $tid) {
            $ids[] = $tid['id'];
        }
        $adds = array();
        $deletes = array();
        foreach ($teacher as $tid) {
            if ( ! in_array($tid, $ids)) {
                $adds[] = $tid;
            }
        }
        foreach ($ids as $tid) {
            if ( ! in_array($tid, $teacher)) {
                $deletes[] = $tid;
            }
        }
        if ($this->checkRepeat($start, $end, $adds) && ! $confirm) {
            return array(
                'type' => 'confirm',
                'msg' => $this->msg,
            );
        }
        $sql = 'UPDATE `exam` `e`
            SET `e`.`name` = :name, `e`.`start` = :start, `e`.`end` = :end, `e`.`address` = :address
            WHERE `e`.`id` = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'name' => $subject,
            'start' => $start,
            'end' => $end,
            'address' => $address,
            'id' => $id,
        ));

        if (count($deletes) != 0) {
            $sql = 'DELETE FROM `exam_teacher`
            WHERE `exam_teacher`.`e_id` = :id
            AND `exam_teacher`.`t_id` IN (' . implode(', ', $deletes) . ')';
            $stmt = $conn->prepare($sql);
            $stmt->execute(array('id' => $id));
        }

        if (count($adds) != 0) {
            $sql = 'INSERT INTO `exam_teacher`(`e_id`, `t_id`) VALUES(:e, :t0)';
            $arr = array(
                'e' => $id,
                't0' => $adds[0]
            );
            for ($i = 1, $loop = count($adds); $i < $loop; $i++) {
                $sql .= ', (:e, :t' . $i . ')';
                $arr['t' . $i] = $adds[1];
            }
            $stmt = $conn->prepare($sql);
            $stmt->execute($arr);
        }
        return true;
    }

    public function deleteExam($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT `e`.`id`
        FROM `exam` `e`
        WHERE `e`.`id` = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'id' => $id,
        ));
        if (count($stmt->fetchAll()) == 0) {
            return false;
        }

        $sql = 'DELETE FROM `exam`
        WHERE `exam`.`id` = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'id' => $id,
        ));

        $sql = 'DELETE FROM `exam_teacher`
        WHERE `exam_teacher`.`e_id` = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'id' => $id,
        ));
        return true;
    }

    public function remindExam($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT `e`.`id`
        FROM `exam` `e`
        WHERE `e`.`id` = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'id' => $id,
        ));
        if (count($stmt->fetchAll()) == 0) {
            return false;
        }

        $sql = 'SELECT `t_id` `id`
        FROM `exam_teacher`
        WHERE `e_id` = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'id' => $id,
        ));
        $ids = array();
        foreach ($stmt->fetchAll() as $tid) {
            $ids[] = $tid['id'];
        }
        return $ids;
    }

    public function insExam($subject, $start, $end, $address, $teacher, $confirm = false)
    {
        if ($this->checkRepeat($start, $end, $teacher) && ! $confirm) {
            return array(
                'type' => 'confirm',
                'msg' => $this->msg,
            );
        }
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'INSERT INTO `exam`(`name`, `address`, `start`, `end`)
            VALUES(:name, :address, :start, :end)
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'name' => $subject,
            'start' => $start,
            'end' => $end,
            'address' => $address,
        ));
        $id = $conn->lastInsertId();
        if (count($teacher) != 0) {
            $sql = 'INSERT INTO `exam_teacher`(`e_id`, `t_id`) VALUES(:e, :t0)';
            $arr = array(
                'e' => $id,
                't0' => $teacher[0]
            );
            for ($i = 1, $loop = count($teacher); $i < $loop; $i++) {
                $sql .= ', (:e, :t' . $i . ')';
                $arr['t' . $i] = $teacher[1];
            }
            $stmt = $conn->prepare($sql);
            $stmt->execute($arr);
        }
        return true;
    }

    private function checkRepeat($start, $end, $teacher)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT `e`.`name` `subject`, `t`.`name`
            FROM `exam` `e`
            JOIN `exam_teacher` `et` ON `e`.`id` = `et`.`e_id`
            JOIN `teacher` `t` ON `et`.`t_id` = `t`.`id`
            WHERE (
                `e`.`start` <= :start
                AND `e`.`start` >= :end
            ) OR (
                `e`.`end` >= :start
                AND `e`.`end` <= :end
            )
        ';
        if (count($teacher) != 0) {
            $sql .= 'AND `t`.`id` IN (' . implode(', ', $teacher) . ')';
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'start' => $start,
            'end' => $end,
        ));
        $repeate = $stmt->fetchAll();
        if (count($repeate) != 0) {
            $this->msg = '';
            foreach ($repeate as $val) {
                $this->msg .= $val['name'] . '老师已在监考' . $val['subject'] . '，时间冲突，是否确认？';
            }
            return true;
        }
        return false;
    }

    private function mergeTable($arr): array
    {
        $rst = array();
        for ($i = 0, $loop = count($arr); $i < $loop; $i++) {
            if ( ! isset($rst[$arr[$i]['id']])) {
                if (null !== $arr[$i]['teacher']) {
                    $teacher = array($arr[$i]['teacher'] . '-' . $arr[$i]['t_id']);
                } else {
                    $teacher = array();
                }
                $rst[$arr[$i]['id']] = array(
                    'key' => (int)$arr[$i]['id'],
                    'id' => (int)$arr[$i]['id'],
                    'subject' => $arr[$i]['name'],
                    'start' => $arr[$i]['start'],
                    'end' => $arr[$i]['end'],
                    'address' => $arr[$i]['address'],
                    'teacher' => $teacher,
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
}
