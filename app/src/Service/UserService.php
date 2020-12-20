<?php

namespace App\Service;

use App\Entity\ToDoList;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Get le user de la todolist
     * 
     * @param ToDoList $todoList
     */
    public function getUserByToDoList(ToDoList $todoList)
    {
        $userRepository = $this->em->getRepository(User::class);
        return $userRepository->findByToDoList($todoList);
    }
}