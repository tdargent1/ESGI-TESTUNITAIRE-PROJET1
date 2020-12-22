<?php

namespace Tests\AppBundle\Entity;

use Carbon\Carbon;
use App\Entity\Item;
use App\Entity\ToDoList;
use App\Service\ItemService;
use App\Service\UserService;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;

class ItemTest extends TestCase
{
    protected $item;
    private $itemService;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->item = new Item();
        $this->item->setName("Important");
        $this->item->setContent("Thibault et Ludovic sont sur un bateau, Ludovic tombe à l'eau.");
        $this->item->setCreatedAt(Carbon::now());

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $this->itemService = new ItemService($entityManager, new UserService($entityManager));
    }

    /** 
     * Vérifie que l'item est valide
     * 
     * @test 
     */ 
    public function testIsItemValid() {
        $this->itemService->expects($this->any())
            ->method('checkIfItemNotExistByName')
            ->willReturn(true);

        $this->assertEmpty($this->item->isValid($this->itemService));
    }

    /** 
     * Vérifie que le nom de l'item est empty
     * 
     * @test 
     */ 
    public function testNameItemEmpty() {
        $this->itemService->expects($this->any())
            ->method('checkIfItemNotExistByName')
            ->willReturn(true);

        $this->item->setName("");

        $this->assertNotEmpty($this->item->isValid($this->itemService));
    }

    /** 
     * Vérifie que le content de l'item est empty
     * 
     * @test 
     */ 
    public function testContentItemEmpty() {
        $this->itemService->expects($this->any())
            ->method('checkIfItemNotExistByName')
            ->willReturn(true);

        $this->item->setContent("");

        $this->assertNotEmpty($this->item->isValid($this->itemService));
    }

    /** 
     * Vérifie que l'item existe déjà
     * 
     * @test 
     */ 
    public function testItemAlreadyExist() {
        $this->itemService->expects($this->any())
            ->method('checkIfItemNotExistByName')
            ->willReturn(false);

        $this->assertNotEmpty($this->item->isValid($this->itemService));
    }

    // /** 
    //  * @test 
    //  */ 
    // public function testIsNameItemValid() {
    //     $this->item->setName("");
    //     $this->assertTrue($this->item->isValid());
    // }

    // /** 
    //  * @test 
    //  */ 
    // public function testIsContentItemNotNull() {
    //     $this->item->setContent("");
    //     $this->assertTrue($this->item->isValid());
    // }
}