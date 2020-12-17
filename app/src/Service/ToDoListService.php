<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\User;
use App\Entity\ToDoList;
use App\Repository\ToDoListRepository;

class ToDoListService
{
    public function add(User $user, Item $item) {
        $toDoList = (new ToDoListRepository())->findBy([
            'user_id' => $user->getId()
        ]);

        $toDoList = current($toDoList);

        $lastUpdate = $toDoList->getUpdatedAt();
    }
}