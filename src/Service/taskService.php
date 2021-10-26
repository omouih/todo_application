<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createOrUpdate(Task $task)
    {
        $this->em->persist($task);
        $this->em->flush();
    }

    public function deleteTask(Task $task)
    {
        $this->em->remove($task);
        $this->em->flush();
    }
}
