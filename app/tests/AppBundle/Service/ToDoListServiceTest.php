<?php

namespace Tests\AppBundle\Service;

use Exception;
use App\Entity\Item;
use App\Entity\User;
use App\Entity\ToDoList;
use App\Service\UserService;
use PHPUnit\Framework\TestCase;
use App\Service\ToDoListService;
use App\Repository\ToDoListRepository;
use App\Service\ItemService;
use App\Service\MailService;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;

class ToDoListServiceTest extends TestCase
{
    private $user;
    private $toDoListService;
    private $toDoListRepository;
    private $itemService;
    private $userService;
    private $mailService;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createMock(User::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $this->itemService = $this->createMock(ItemService::class);
        $this->userService = $this->createMock(UserService::class);
        $this->mailService = $this->createMock(MailService::class);
        
        $this->toDoListService = new ToDoListService(
            $entityManager, 
            $this->itemService, 
            $this->mailService, 
            $this->userService
        );

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

    public function getNewTodoList()
    {
        $todoList = new ToDoList($this->user);
        $todoList->setName("nom");
        $todoList->setDescription("description");
        
        return $todoList;
    }

    public function getNewItem()
    {
        $item = new Item();
        $item->setName("itemName");
        $item->setContent("itemContent");
        
        return $item;
    }


    /********************  createToDoList()  ***********************/
    /** 
     * test la création d'une ToDoList valide
     * 
     * @test 
     */
    public function testCreateValidToDoList()
    {
        // pas d'erreur : valide
        $this->userService->expects($this->any())
            ->method('isValid')
            ->willReturn([]);

        // aucune todolist n'existe pour l'utilisateur
        $this->toDoListRepository->expects($this->any())
        ->method('findOneByUserId')
        ->willReturn(null);

        // On attend que la méthode nous retourne notre todoList 
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
        // todoList non valide car user non valide
        $this->userService->expects($this->any())
            ->method('isValid')
            ->willReturn(['error']);

        // aucune todolist n'existe pour l'utilisateur
        $this->toDoListRepository->expects($this->any())
        ->method('findOneByUserId')
        ->willReturn(null);

        $this->expectException(Exception::class);
        $this->toDoListService->createToDoList($this->user, "Ma première ToDoList", "Une superbe ToDoList");
    }

    /** 
     * Test si une todoList existe déjà pour l'utilisateur
     * 
     * @test 
     */
    public function testToDoListAlreadyExist()
    {
        // pas d'erreur : valide
        $this->userService->expects($this->any())
            ->method('isValid')
            ->willReturn([]);

        $todoList = $this->getNewTodoList();

        // une todoList existe déjà pour l'utilisateur
        $this->toDoListRepository->expects($this->any())
            ->method('findOneByUserId')
            ->willReturn($todoList);

        $this->expectException(Exception::class);
        $this->toDoListService->createToDoList($this->user, "Ma première ToDoList", "Une superbe ToDoList");
    }


    /********************  addItem()  ***********************/
        // $this->checkTimeBetweenAdding
        // $this->checkIsToDoListFull
        // $item->isValid


    /** 
     * Test ajout d'un item sans erreur
     * 
     * @test 
     */
    public function testAddItemWithoutError()
    {
        $todoList = $this->getNewTodoList();
        $todoList->setLastAddedTime(Carbon::create(2000, 1, 1, 0, 0, 0));
        $item = $this->getNewItem();

        $this->itemService->expects($this->any())
            ->method('isValid')
            ->willReturn([]);
        
        $this->mailService->expects($this->any())
            ->method('envoieMail')
            ->willReturn(true);

        $this->toDoListRepository->expects($this->any())
            ->method('updateToDoList')
            ->willReturn($todoList);

        $todoList = $this->toDoListService->addItem($todoList, $item);

        $item->setToDoList($todoList);

        fwrite(STDERR, print_r($todoList->getItems(0), TRUE));
        fwrite(STDERR, print_r($item, TRUE));
        $this->assertTrue($todoList->getItems()->contains($item));
    }
}