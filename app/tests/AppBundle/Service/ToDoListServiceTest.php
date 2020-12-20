<?php

namespace Tests\AppBundle\Service;

use Exception;
use Carbon\Carbon;
use App\Entity\User;
use App\Entity\ToDoList;
use App\Service\UserService;
use PHPUnit\Framework\TestCase;
use App\Service\ToDoListService;
use App\Repository\ToDoListRepository;
use Doctrine\ORM\EntityManagerInterface;

class ToDoListServiceTest extends TestCase
{
    private $user;
    private $todoList;
    private $userRepository;
    private $userService;
    private $toDoListService;
    private $toDoListRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createMock(User::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $this->toDoListService = new ToDoListService($entityManager);

        $this->toDoListRepository = $this->createMock(ToDoListRepository::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->toDoListRepository);

        // $this->user = new User();
        // $this->user->setFirstName('Thibault');
        // $this->user->setLastName('Dargent');
        // $this->user->setEmail('tdargent@gmail.com');
        // $this->user->setPassword('password123');
        // $this->user->setBirthday(Carbon::create(1999, 9, 21));
    }

    /** 
     * test la création d'une ToDoList
     * 
     * @test 
     */
    public function testCreateToDoList()
    {
        $this->user->expects($this->any())
            ->method('isValid')
            ->willReturn([]);

        $this->toDoListRepository->expects($this->any())
        ->method('findOneByUserId')
        ->willReturn(null);

        $toDoList = new ToDoList($this->user);
        $toDoList->setName("nom");
        $toDoList->setDescription("description");

        $this->assertInstanceOf(
            ToDoList::class, 
            $this->toDoListService->createToDoList($this->user, "Ma première ToDoList", "Une superbe ToDoList")
        );
    }

    /** 
     * test la création d'une ToDoList avec un user non valide
     * 
     * @test 
     */
    public function testCreateToDoListUserNotValid()
    {
        $this->user->expects($this->any())
            ->method('isValid')
            ->willReturn(['error']);

        $this->toDoListRepository->expects($this->any())
        ->method('findOneByUserId')
        ->willReturn(null);

        $toDoList = new ToDoList($this->user);
        $toDoList->setName("nom");
        $toDoList->setDescription("description");

        $this->expectException(Exception::class);
        $this->toDoListService->createToDoList($this->user, "Ma première ToDoList", "Une superbe ToDoList");
    }



    /** 
     * test si la ToDoList existe déjà
     * 
     * @test 
     */
    public function testToDoListAlreadyExist()
    {
        $this->user->expects($this->any())
            ->method('isValid')
            ->willReturn([]);

        $toDoList = new ToDoList($this->user);
        $toDoList->setName("Ma première ToDoList");
        $toDoList->setDescription("Une superbe ToDoList");

        $this->toDoListRepository->expects($this->any())
        ->method('findOneByUserId')
        ->willReturn($toDoList);

        $this->expectException(Exception::class);
        $this->toDoListService->createToDoList($this->user, "Ma première ToDoList", "Une superbe ToDoList");
    }
}