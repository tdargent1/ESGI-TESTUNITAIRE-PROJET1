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
     * 
     * @return bool
     */
    public function checkTimeBetweenAdding(ToDoList $toDoList, Item $item): bool
    {
        $lastAddedTime = Carbon::createFromTimestamp($toDoList->getLastAddedTime())->addMinutes(30);
        
        if($lastAddedTime->isAfter(Carbon::now()))
            throw new Exception("Vous devez attendre 30 minutes avant d'ajouter un nouvel élément");

        return true;
    }

    /**
     * Vérifie si il y a 8 items dans la ToDoList pour l'envoi du mail
     * 
     * @param User $user
     */
    public function checkEnvoieMail(User $user) {
        $toDoList = $this->getToDoListByUserId($user);

        if(count($toDoList->getItems()->toArray()) == 8) {
            $mailService = new MailService();
            $mailService->envoieMail(
                $user->getEmail(), 
                "ToDoList - Alerte", 
                "Vous venez d'ajouter un huitième élément à votre ToDoLis"
            );
        }
    }
}