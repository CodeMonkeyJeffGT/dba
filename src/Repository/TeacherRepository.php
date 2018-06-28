<?php

namespace App\Repository;

use App\Entity\Teacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TeacherRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Teacher::class);
    }

    public function login($account, $password)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT `id`
            FROM `teacher`
            WHERE `account` = :account
            AND `password` = :password
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'account' => $account,
            'password' => $password,
        ));
        if (count($id = $stmt->fetchAll())) {
            return $id[0]['id'];
        } else {
            return false;
        }
    }

    public function checkPermit($id): bool
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT `admin`
            FROM `teacher`
            WHERE `id` = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'id' => $id,
        ));
        try {
            if ($stmt->fetchAll()[0]['admin']) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
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
        $sql = 'SELECT `t1`.`id` `id`, `t1`.`name` `name`, `t1`.`account` `account`, `t1`.`phone` `phone`, `t1`.`admin` `admin`
        FROM `teacher` `t1`
        WHERE `t1`.`name` LIKE :search
        UNION
        SELECT `t2`.`id` `id`, `t2`.`name` `name`, `t2`.`account` `account`, `t2`.`phone` `phone`, `t2`.`admin` `admin`
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

    public function viewSelf($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT `t`.`name` `name`, `t`.`account` `account`, `t`.`phone` `phone`, `t`.`admin` `admin`
        FROM `teacher` `t`
        WHERE `t`.`id` = :id
        ';
        $rst = $stmt->fetchAll();
        return $rst[0];
    }

    public function editTeacher($id, $name, $phone, $admin)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT `t`.`id`
        FROM `teacher` `t`
        WHERE `t`.`id` = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'id' => $id,
        ));
        if (count($stmt->fetchAll()) == 0) {
            return array(
                'type' => 'error',
                'msg' => '所选教师不存在',
            );
        }

        $sql = 'UPDATE `teacher` `t`
            SET `t`.`name` = :name, `t`.`phone` = :phone, `t`.`admin` = :admin
            WHERE `t`.`id` = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'name' => $name,
            'phone' => $phone,
            'admin' => (int)$admin,
            'id' => $id,
        ));
        return true;
    }

    public function editSelf($id, $phone)
    {
        $sql = 'UPDATE `teacher` `t`
            SET `t`.`phone` = :phone
            WHERE `t`.`id` = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'phone' => $phone,
            'id' => $id,
        ));
        return true;
    }

    public function deleteTeacher($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT `t`.`id`
        FROM `teacher` `t`
        WHERE `t`.`id` = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'id' => $id,
        ));
        if (count($stmt->fetchAll()) == 0) {
            return array(
                'type' => 'error',
                'msg' => '所选教师不存在',
            );
        }

        $sql = 'DELETE FROM `teacher`
            WHERE `teacher`.`id` = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'id' => $id,
        ));
        return true;
    }

    public function newTeacher($name, $account, $password, $phone, $admin)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT `t`.`id`
        FROM `teacher` `t`
        WHERE `t`.`account` = :account';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'account' => $account,
        ));
        if (count($stmt->fetchAll()) != 0) {
            return array(
                'type' => 'error',
                'msg' => '账号已存在，请使用其他账号',
            );
        }

        $sql = 'INSERT INTO `teacher`(`name`, `account`, `password`, `phone`, `admin`)
        VALUES(:name, :account, :password, :phone, :admin)';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'name' => $name,
            'account' => $account,
            'phone' => $phone,
            'password' => $password,
            'admin' => (int)$admin,
        ));
        return true;
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
                'admin' => $arr[$i]['admin'] ? '是' : '否',
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
}
