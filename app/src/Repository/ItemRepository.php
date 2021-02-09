<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\User;
use App\Entity\ToDoList;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    /**
     * @return Item[] Returns an array with the user's todoList
     */
    public function findOneByNameAndToDoList(String $name, ToDoList $toDoList)
    {
        return $this->createQueryBuilder('item')
        ->innerJoin(ToDoList::class, 'todoList', 'WITH', 'item.toDoList = todoList.id')
        ->andWhere('item.name = :name')
        ->andWhere('todoList.id = :todolist_id')
        ->setParameter('name', $name)
        ->setParameter('todolist_id', $toDoList->getId())
        ->getQuery()
        ->getResult();
    }

    public function updateItem(Item $item): Item
    {
        // $this->_em->persist($item);
        $this->_em->flush();

        return $item;
    }
}
