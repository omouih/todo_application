<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
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
    public function create(Request $request): Response
    {
        $title = trim($request->request->get('title'));
        
        if(empty($title))

            return $this->redirectToRoute('list');

        $em = $this->getDoctrine()->getManager();

        $task = new Task;
        
        $task->setTitle($title);
        $em->persist($task);
        $em->flush();

        return $this->redirectToRoute('list');
        
    }

    /**
     * @Route("switchStatus/{id}", name="switchStatus")
     */
    public function switchStatus($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository(Task::class)->find($id);

        $task->setStatus( ! $task->getStatus() );
        $em->flush();

        return $this->redirectToRoute('list');
    }


     /**
     * @Route("/delete/{id}", name="deleteTask")
     */
    public function delete(Task $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($id);
        $em->flush();

        return $this->redirectToRoute('list');

    }
} 
