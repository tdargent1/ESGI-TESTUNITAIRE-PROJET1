<?php

namespace Tests\AppBundle\Service;

use Carbon\Carbon;
use App\Entity\Item;
use App\Entity\ToDoList;
use App\Entity\User;
use App\Service\ItemService;
use App\Service\UserService;
use PHPUnit\Framework\TestCase;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ItemServiceTest extends TestCase
{
    private $item;
    private $user;
    private $itemService;
    private $itemRepository;
    private $todoList;
    
    public function setUp(): void
    {
        parent::setUp();
        
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $this->itemRepository = $this->createMock(ItemRepository::class);
        
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->itemRepository);
        
        $this->itemService = new ItemService($entityManager, new UserService($entityManager));

        $this->user = new User();
        $this->user->setFirstName('Thibault');
        $this->user->setLastName('Dargent');
        $this->user->setEmail('tdargent@gmail.com');
        $this->user->setPassword('password123');
        $this->user->setBirthday(Carbon::create(1999, 9, 21));

        $this->item = new Item();
        $this->item->setName("Item1");
        $this->item->setContent("Content de Item1");
        $this->item->setCreatedAt(Carbon::now());

        $this->todoList = new ToDoList($this->user);
        $this->todoList->setName("nom");
        $this->todoList->setDescription("description");
    }

    /** 
     * test lorsque l'item n'est affecté à aucune todolist
     * 
     * @test 
     */
    public function testItemNotAffectedToToDoList()
    {
        $this->itemRepository->expects($this->any())
            ->method('findOneByNameAndToDoList')
            ->willReturn($this->item);

        $this->expectException(Exception::class);
        $this->itemService->checkIfItemNotExistByName($this->item);
    }

    /** 
     * Test lorsque aucun item du même nom n'existe
     * 
     * @test 
     */
    public function testIfItemNotExist()
    {
        $this->item->setToDoList($this->todoList);
        $this->itemRepository->expects($this->any())
            ->method('findOneByNameAndToDoList')
            ->willReturn(null);
        
        $this->assertTrue($this->itemService->checkIfItemNotExistByName($this->item));
    }

    public function testIfItemExist()
    {
        $item = new Item();
        $item->setName("Item1");
        $item->setContent("Content de Item1");
        $item->setCreatedAt(Carbon::now());
        $item->setToDoList($this->todoList);

        $this->item->setToDoList($this->todoList);

        $this->itemRepository->expects($this->any())
            ->method('findOneByNameAndToDoList')
            ->willReturn($item);

        $this->assertFalse($this->itemService->checkIfItemNotExistByName($this->item));
    }

    /** 
     * Vérifie que l'item est valide
     * 
     * @test 
     */ 
    public function testIsItemValid() {
        $this->itemRepository->expects($this->any())
            ->method('findOneByNameAndToDoList')
            ->willReturn(null);
        
        $this->item->setToDoList($this->todoList);

        $this->assertEmpty($this->itemService->isValid($this->item));
    }

    /** 
     * Vérifie que le nom de l'item est empty
     * 
     * @test 
     */ 
    public function testNameItemEmpty() {
        $this->itemRepository->expects($this->any())
            ->method('findOneByNameAndToDoList')
            ->willReturn(null);
        
        $this->item->setToDoList($this->todoList);

        $this->item->setName("");

        $this->assertNotEmpty($this->itemService->isValid($this->item));
    }

    /** 
     * Vérifie que le content de l'item est empty
     * 
     * @test 
     */ 
    public function testContentItemEmpty() {
        $this->itemRepository->expects($this->any())
        ->method('findOneByNameAndToDoList')
        ->willReturn(null);
    
        $this->item->setToDoList($this->todoList);

        $this->item->setContent("");

        $this->assertNotEmpty($this->itemService->isValid($this->item));
    }

    /** 
     * Vérifie que le content de l'item est empty
     * 
     * @test 
     */
    public function testContentItemLengthUpTo1000Characters()
    {
        $this->itemRepository->expects($this->any())
            ->method('findOneByNameAndToDoList')
            ->willReturn(null);

        $this->item->setToDoList($this->todoList);

        $this->item->setContent(str_repeat ("0123456789", 101));
        
        $this->assertNotEmpty($this->itemService->isValid($this->item));
    }

    /** 
     * Vérifie que l'item existe déjà
     * 
     * @test 
     */ 
    public function testItemAlreadyExist() {
        $this->itemRepository->expects($this->any())
        ->method('findOneByNameAndToDoList')
        ->willReturn($this->item);
    
        $this->item->setToDoList($this->todoList);

        $this->assertNotEmpty($this->itemService->isValid($this->item));
    }
}
