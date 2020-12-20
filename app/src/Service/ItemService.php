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
     * Vérifie si un item du même existe en DB
     * 
     * @param Item $name
     */
    public function checkIfExistByName(Item $item)
    {
        $itemRepository = $this->em->getRepository(Item::class);
        $user = $this->userService->getUserByToDoList($item->getToDoList());

        if (!empty($itemRepository->findOneByNameAndUser($item->getName(), $user)))
            throw new Exception("Un item du même nom existe déjà");
    }
}