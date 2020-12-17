<?php

namespace Tests\AppBundle\Entity;

use App\Entity\Item;
use Carbon\Carbon;
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
        $this->item->setToDoList();
    }

    /** 
     * @test 
     */ 
    public function testIsItemValid() {
        $this->assertTrue($this->item->isValid());
    }

    /** 
     * @test 
     */ 
    public function testIsNameItemValid() {
        $this->item->setName("");
        $this->assertTrue($this->item->isValid());
    }

    /** 
     * @test 
     */ 
    public function testIsContentItemNotNull() {
        $this->item->setContent("");
        $this->assertTrue($this->item->isValid());
    }
}