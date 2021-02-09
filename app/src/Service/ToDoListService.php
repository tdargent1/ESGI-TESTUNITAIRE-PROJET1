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
    private $itemService;
    private $mailService;
    private $userService;

    public function __construct(EntityManagerInterface $em, ItemService $itemService,
                                MailService $mailService, UserService $userService)
    {
        $this->em = $em;
        $this->itemService = $itemService;
        $this->mailService = $mailService;
        $this->userService = $userService;
    }

    public function createToDoList(User $user, String $name, String $description): ToDoList
    {
        $isValid = $this->userService->isValid($user);

        if (!empty($isValid))
            throw new Exception(implode('\n', $isValid));

        if (!empty($this->getToDoListByUserId($user)))
            throw new Exception("Impossible de créer une TodoList. L'utilisateur a déjà une TodoList");

        return $this->em->getRepository(ToDoList::class)->createToDoList($user, $name, $description); 
    }

    public function addItem(ToDoList $todoList, Item $item)
    {
        $this->checkTimeBetweenAdding($todoList);
        $this->checkIsToDoListFull($todoList);
        $this->itemService->isValid($item, $todoList);

        $todoList->getItems()->add($item);
        $todoList->setLastAddedTime(Carbon::now());
       
        $item->setToDoList($todoList);
        $item->setCreatedAt(Carbon::now());

        $this->em->getRepository(ToDoList::class)->updateToDoList($todoList);

        if ($this->checkEnvoieMail($todoList)) {
            $this->mailService->envoieMail(
                $this->user->getEmail(),
                "ToDoList - Alerte",
                "Vous venez d'ajouter un huitième élément à votre ToDoList"
            );
        }
        
        return $todoList;
    }

    /**
     * Supprime l'item en argument de la todoList
     * 
     * @param ToDoList $todoList
     * @param Item $item
     * 
     * @return ?TodoList
     */
    public function removeItem(ToDoList $todoList, Item $item): ?ToDoList
    {
        if($todoList->getItems()->removeElement($item)) {
            if($item->getToDoList() === $todoList) {
                
                $this->em->getRepository(Item::class)->removeItem($item);

                return $todoList;
            }
        }

        return null;
    }

    /**
     * Check si la todoList est valide
     * 
     * @param ToDoList $todoList
     * 
     */
    public function isValid(ToDoList $todoList)
    {
        $exceptions = [];

        if (empty($todoList->getName()))
            array_push($exceptions, "Nom vide.");

        if (empty($todoList->getDescription()))
            array_push($exceptions, "Description vide.");

        return $exceptions;
    }

    /**
     * Retourne la ToDoList du User
     * 
     * @param User $user
     * 
     * @return TodDoList
     */
    public function getToDoListByUserId(User $user): ?ToDoList
    {
        $toDoListRepository = $this->em->getRepository(ToDoList::class);

        return $toDoListRepository->findOneByUserId($user->getId());
    }

    /**
     * Check si le dernier ajout date d'au moins 30 minutes
     * 
     * @param ToDoList $toDoList
     */
    public function checkTimeBetweenAdding(ToDoList $todoList)
    {
        if(empty($todoList->getLastAddedTime()))
            return;
        
        $lastAddedTimePlus30min = Carbon::instance($todoList->getLastAddedTime())->addMinutes(30);

        if(Carbon::now()->isBefore($lastAddedTimePlus30min))
            throw new Exception("Vous devez attendre 30 minutes avant d'ajouter un nouvel élément");
    }

    /**
     * Vérifie si il y a 8 items dans la ToDoList pour l'envoi du mail
     * 
     * @param ToDoList $todoList
     */
    public function checkEnvoieMail(ToDoList $toDoList)
    {
        return count($toDoList->getItems()) == 8;
    }

    /**
     * Check la taille de la todolist (max 10)
     * 
     * @param ToDoList $todoList
     */
    public function checkIsToDoListFull(ToDoList $toDoList)
    {
        if (count($toDoList->getItems()) == 10)
            throw new Exception("La ToDoList peut contenir au maximum 10 items.");
    }
}