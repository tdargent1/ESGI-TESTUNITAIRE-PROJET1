<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ToDoListController
 * @package App\Controller
 *
 * @Route("/todolist", name="todolist_")
 */
class ToDoListController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     *
     * Call BookRepository with Autowiring
     */
    public function index(): Response
    {
        return $this->render('front/todolist/index.html.twig', [
            'controller_name' => 'ToDoListController',
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     */
    public function edit(Book $book, Request $request)
    {
    }

    /**
     * @Route("/delete/{id}/{token}", name="delete", methods={"GET"})
     */
    public function delete(Book $book, $token)
    {
    }
}
