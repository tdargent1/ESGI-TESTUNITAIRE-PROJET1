<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\ToDoList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

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



    // public function createItem(User $user, String $name, String $description): ToDoList
    // {
    //     $item = new Item($user);
    //     $toDoList->setName($name);
    //     $toDoList->setDescription($description);

    //     $this->_em->persist($toDoList);
    //     $this->_em->flush();

    //     return $toDoList;
    // }   
}
