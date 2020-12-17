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
        
        if(checkTimeBetweenAdding(current($toDoList), $item)) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            checkEnvoieMail();
        }
    }

    public function checkTimeBetweenAdding(ToDoList $toDoList, Item $item): bool
    {
        if(Carbon::createFromTimestamp($ToDoList->getLastAddedTime)->toDateTimeString()->addMinutes(30).isAfter(
            Carbon::createFromTimestamp($item->getCreatedDate)->toDateTimeString())) {
            throw new Exception("Vous devez attendre 30 minutes avant d'ajouter un nouvel élément");
        }

        return true;
    }

    public function checkEnvoieMail() {

    }
}