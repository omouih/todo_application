<?php

namespace App\Controller;
use App\Service\taskService;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TaskType;


class TaskController extends AbstractController
{

    
    
    
     /**
     * @Route("/list", name="list")
     */
    public function index(): Response
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findBy([],
            ['id' =>'DESC']);

        return $this->render('index.html.twig',[
            'tasks' =>$tasks
        ]);
    }

    /**
     * @Route("/create", name="createTask", methods={"POST"})
     */
    public function create(taskService $taskService, Request $request, EntityManagerInterface $em)
    {
            $title = trim($request->request->get('title')); 
            if(empty($title)){
    
                return $this->redirectToRoute('list');
            }

            $task = new Task();
            $form = $this->createForm(TaskType::class, $task);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) 
            {
                 $taskService->createORupdate($em);
            }

            return $this->redirectToRoute('list');
            
            return $this->render('index.html.twig', [
                'task' => $task,
                'Taskform' => $form->createView(),
            ]);
    }

        
    

    /**
     * @Route("switchStatus/{id}", name="switchStatus")
     */
    public function switchStatus(taskService $taskService): Response
    {
        $taskService->StatusTask();
      

        return $this->redirectToRoute('list');
    }


     /**
     * @Route("/delete/{id}", name="deleteTask", methods="DELETE")
     */
    public function delete(Request $request, Task $task, taskService $taskService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            
            $taskService->deleteTask(); 
        }

        return $this->redirectToRoute('list');

    }
} 
