<?php

namespace App\Service;

use Exception;
use App\Entity\Item;
use App\Entity\ToDoList;
use Doctrine\ORM\EntityManagerInterface;

class ItemService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    /**
     * Return true si le nom n'existe pas déjà
     * 
     * @param Item $item
     */
    public function checkIfItemNotExistByName(Item $item, ToDoList $toDoList)
    {
        $itemRepository = $this->em->getRepository(Item::class);
        
        return empty($itemRepository->findOneByNameAndToDoList($item->getName(), $toDoList));
    }


    /**
     * Check si l'item est valide
     * 
     * @param Item $item
     */
    public function isValid(Item $item, ToDoList $toDoList)
    {
        $exceptions = [];

        if (empty($item->getName()))
            array_push($exceptions, "Nom vide.");

        if (!$this->checkIfItemNotExistByName($item, $toDoList))
            array_push($exceptions, "Nom déjà utilisé.");

        if (empty($item->getContent()))
            array_push($exceptions, "Content vide.");

        if (strlen($item->getContent()) > 1000)
            array_push($exceptions, "Content trop long (maximum 1000 caractères).");

        return $exceptions;
    }
}