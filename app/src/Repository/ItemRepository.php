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
    public function findOneByNameAndUser(String $name, User $user)
    {
        return $this->createQueryBuilder('item')
        ->innerJoin(ToDoList::class, 'todoList')
        ->innerJoin(User::class, 'user')
        ->andWhere('item.name = :name')
        ->andWhere('user.id = :user_id')
        ->setParameter('name', $name)
        ->setParameter('user_id', $user->getId())
        ->getQuery()
        ->getResult();
        // ->innerJoin(ToDoList::class, 'todoList', Join::WITH, 'item.to_do_list_id = todoList.id')
    }

    public function updateItem(Item $item): Item
    {
        // $this->_em->persist($item);
        $this->_em->flush();

        return $item;
    }
}
