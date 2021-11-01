<?php

namespace App\Controller;

use App\Service\TaskService;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TaskType;

/**
 * @Route("/task", name="task")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/", name="_list")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $tasks = $em->getRepository(Task::class)->findBy(
            [],
            ['id' => 'DESC']
        );

        return $this->render('task/list.html.twig', [
            'tasks' => $tasks
        ]);
    }

    /**
     * @Route("/create", name="_create", methods={"GET", "POST"})
     * @Route("/edit/{id}", name="_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Task $task = null, TaskService $taskService)
    {
        if ($task === null) {
            $task = new Task();
            $formAction = $this->generateUrl('task_create');
        } else {
            $formAction = $this->generateUrl('task_edit', ['id' => $task->getId()]);
        }

        $form = $this->createForm(TaskType::class, $task, [
            'action' => $formAction
        ]);

        if ($form->handleRequest($request) && $form->isSubmitted() && $form->isValid()) {
            $taskService->createORupdate($form->getData());

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'Taskform' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete", methods="POST")
     */
    public function delete(Request $request, Task $task, taskService $taskService): Response
    {
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            $taskService->deleteTask($task);
        }

        return $this->redirectToRoute('task_list');
    }
}
