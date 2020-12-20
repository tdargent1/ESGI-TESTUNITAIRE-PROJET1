<?php

namespace App\Service;

use Exception;
use Carbon\Carbon;
use App\Entity\Item;
use App\Entity\User;
use App\Entity\ToDoList;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;

class ToDoListService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createToDoList(User $user, String $name, String $description): ToDoList
    {
        $isValid = $user->isValid();

        if (!empty($isValid))
            throw new Exception(implode("\n", $isValid));

        if (!empty($this->getToDoListByUserId($user)))
            throw new Exception("Impossible de créer une TodoList. L'utilisateur a déjà une TodoList");

        $toDoList = new ToDoList($user);
        $toDoList->setName($name);
        $toDoList->setDescription($description);

        $this->em->persist($toDoList);
        $this->em->flush();

        return $toDoList;
    }

    /**
     * Retourne la ToDList du User
     * 
     * @param User $user
     * 
     * @return TodDoList
     */
    public function getToDoListByUserId(User $user) :ToDoList
    {
        $toDoListRepository = $this->em->getRepository(ToDoList::class);

        return $toDoListRepository->findOneByUserId($user->getId());;
    }

    /**
     * Check si le dernier ajout date d'au moins 30 minutes
     * 
     * @param ToDoList $toDoList
     * @param Item $item
     */
    public function checkTimeBetweenAdding(ToDoList $toDoList, Item $item): bool
    {
        $lastAddedTime = Carbon::createFromTimestamp($toDoList->getLastAddedTime())->addMinutes(30);
        
        if($lastAddedTime->isAfter(Carbon::now()))
            throw new Exception("Vous devez attendre 30 minutes avant d'ajouter un nouvel élément");
    }

    /**
     * Vérifie si il y a 8 items dans la ToDoList pour l'envoi du mail
     * 
     * @param User $user
     */
    public function checkEnvoieMail(User $user) {
        $toDoList = $this->getToDoListByUserId($user);

        if(count($toDoList->getItems()) == 8) {
            return true;
        }

        return false;
    }
}