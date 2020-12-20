<?php

namespace App\Service;

use Exception;
use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;

class ItemService
{
    private $em;
    private $userService;

    public function __construct(EntityManagerInterface $em, UserService $userService)
    {
        $this->em = $em;
        $this->userService = $userService;
    }
    
    /**
     * Return true si le nom n'existe pas déjà
     * 
     * @param Item $name
     */
    public function checkIfItemNotExistByName(Item $item)
    {
        $itemRepository = $this->em->getRepository(Item::class);
        
        $todoList = $item->getToDoList();

        if ($todoList == null)
            throw new Exception('L\'item n\'est lié à aucune TodoList.');
        
        return empty($itemRepository->findOneByNameAndUser($item->getName(), $todoList->getOwner()));
    }
}