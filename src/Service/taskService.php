<?php

namespace App\Service;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;



class taskService{

    
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        
    }

    public function createORupdate()
    {
        $task = new Task;
        $task->setTitle($title);
        $this->em->persist($task);
        $this->em->flush(); 
                     
    }

    public function StatusTask($id){
        $task = $this->em->getRepository(Task::class)->find($id);

        $task->setStatus( ! $task->getStatus() );
        $this->em->flush();
    }
    public function deleteTask(Task $task){
        
        $this->em->remove($task);
        $this->em->flush();
    }



}