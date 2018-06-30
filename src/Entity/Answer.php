<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnswerRepository")
 */
class Answer
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
    private $t_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $task_id;

    /**
     * @ORM\Column(type="text")
     */
    private $answer;

    /**
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created;

    public function getId()
    {
        return $this->id;
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

    public function getTaskId(): ?int
    {
        return $this->task_id;
    }

    public function setTaskId(int $task_id): self
    {
        $this->task_id = $task_id;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }
}
