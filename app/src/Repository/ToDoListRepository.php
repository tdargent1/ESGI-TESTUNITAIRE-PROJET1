<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\ToDoList;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ToDoList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToDoList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToDoList[]    findAll()
 * @method ToDoList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToDoListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToDoList::class);
    }

    public function updateToDoList(ToDoList $toDoList): ToDoList
    {
        // $this->_em->persist($toDoList);
        $this->_em->flush();

        return $toDoList;
    }

    public function createToDoList(User $user, String $name, String $description): ToDoList
    {
        $toDoList = new ToDoList($user);
        $toDoList->setName($name);
        $toDoList->setDescription($description);

        $this->_em->persist($toDoList);
        $this->_em->flush();

        return $toDoList;
    }

    public function findOneByUserId($userId): ?ToDoList
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.owner = :userId')
            ->setParameter('userId', $userId)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }
}
