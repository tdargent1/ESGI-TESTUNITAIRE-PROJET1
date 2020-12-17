<?php

namespace Tests\AppBundle\Entity;

use Carbon\Carbon;
use App\Entity\Item;
use App\Entity\ToDoList;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    protected $item;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->item = new Item();
        $this->item->setName("Important");
        $this->item->setContent("Thibault et Ludovic sont sur un bateau, Ludovic tombe à l'eau.");
        $this->item->setCreatedDate(Carbon::now());
        $this->item->setToDoList((new ToDoList())
            ->setName("Thibault")
            ->setDescription("Mon contenu")
        );
    }

    /** 
     * @test 
     */ 
    public function testIsItemValid() {
        $this->assertTrue($this->item->isValid());
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