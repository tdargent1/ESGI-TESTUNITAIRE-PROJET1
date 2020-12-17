<?php

namespace App\Tests;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    protected $item;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->item->setName("Important");
        $this->item->setContent("Thibault et Ludovic sont sur un bateau, Ludovic tombe à l'eau.");
        $this->item->setCreatedDate(Carbon::now());
        $this->item->setToDoList();
    }

    /** 
     * @test 
     */ 
    public function isItemValid() {
        $this->assertTrue($this->item->isValid());
    }

    /** 
     * @test 
     */ 
    public function isNameItemValid() {
        $this->item->setName("");
        $this->assertTrue($this->item->isValid());
    }

    /** 
     * @test 
     */ 
    public function isContentItemNotNull() {
        $this->item->setContent("");
        $this->assertTrue($this->item->isValid());
    }
}