<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExamTeacherRepository")
 */
class ExamTeacher
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $e_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $t_id;

    public function getId()
    {
        return $this->id;
    }

    public function getEId(): ?int
    {
        return $this->e_id;
    }

    public function setEId(int $e_id): self
    {
        $this->e_id = $e_id;

        return $this;
    }

    public function getTId(): ?int
    {
        return $this->t_id;
    }

    public function setTId(int $t_id): self
    {
        $this->t_id = $t_id;

        return $this;
    }
}
