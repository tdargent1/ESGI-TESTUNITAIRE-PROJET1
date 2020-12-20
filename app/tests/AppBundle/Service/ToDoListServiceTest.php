<?php

namespace Tests\AppBundle\Service;

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

        $this->assertEquals(
            $toDoList, 
            $this->toDoListService->createToDoList($this->user, "Ma première ToDoList", "Une superbe ToDoList")
        );
    }
}