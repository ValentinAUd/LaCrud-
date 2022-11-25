<?php

namespace App\Controller;

use App\Entity\ToDoList;
use App\Form\ToDoListType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class ToDoListController extends AbstractController
{
    #[Route('/todolist/create', name: 'todolist_create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $toDoList = new ToDoList();
        $form = $this->createForm(ToDoListType::class, $toDoList);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump($toDoList);
            $em = $doctrine->getManager();
            $em->persist($toDoList);
            $em->flush();
            return $this->redirectToRoute("todolist_readAll");
        }


        return $this->render('to_do_list/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/todolist/read/{id}', name: 'todolist_read')] // accolades pour parametres
    public function read(ToDoList $toDoList)
    {
        return $this->render('to_do_list/read.html.twig', [
            'todolist' => $toDoList
        ]);
    }

    #[Route('/todolist/update/{id}', name: 'todolist_update')]
    public function update(ToDoList $toDoList, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(ToDoListType::class, $toDoList);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump($toDoList);
            $em = $doctrine->getManager();
            $em->flush();
        }


        return $this->render('to_do_list/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/todolist/readAll', name: 'todolist_readAll')] // accolades pour parametres
    public function readAll(ManagerRegistry $doctrine)
    {
        $todolistRepository = $doctrine->getRepository(ToDoList::class);
        return $this->render('to_do_list/readAll.html.twig', [
            'lists' => $todolistRepository->findAll()
        ]);
    }
    #[Route('/todolist/delete/{id}', name: 'todolist_delete')]
    public function delete(ToDoList $toDoList, ManagerRegistry $doctrine)
    {

        $em = $doctrine->getManager();
        $em->remove($toDoList);
        $em->flush();
        return $this->redirectToRoute("todolist_readAll");
    }
}
