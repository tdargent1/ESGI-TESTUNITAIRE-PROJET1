<?php

namespace App\Service;

use Exception;
use App\Entity\Item;
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
    public function checkIfItemNotExistByName(Item $item)
    {
        $itemRepository = $this->em->getRepository(Item::class);
        
        $todoList = $item->getToDoList();

        if ($todoList == null)
            throw new Exception('L\'item n\'est lié à aucune TodoList.');
        
        return empty($itemRepository->findOneByNameAndUser($item->getName(), $todoList->getOwner()));
    }


    /**
     * Check si l'item est valide
     * 
     * @param Item $item
     */
    public function isValid(Item $item)
    {
        $exceptions = [];

        if (empty($item->name))
            array_push($exceptions, "Nom vide.");

        if (!$this->checkIfItemNotExistByName($item))
            array_push($exceptions, "Nom déjà utilisé.");

        if (empty($item->content))
            array_push($exceptions, "Content vide.");

        if (strlen($item->content) > 1000)
            array_push($exceptions, "Content trop long (maximum 1000 caractères).");

        return $exceptions;
    }
}