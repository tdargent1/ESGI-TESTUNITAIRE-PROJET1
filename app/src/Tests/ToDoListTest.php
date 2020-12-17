<?php

namespace App\Tests;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ToDoListTest extends TestCase
{
    protected $toDoList;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->toDoList->setName("La numéro uno");
        $this->toDoList->setDescription("Ma première TODOLIST");
        $this->toDoList->setCreatedDate(Carbon::now());
        $this->toDoList->setToDoList();
    }

    /** 
     * @test 
     */ 
    public function istoDoListValid() {
        $this->assertTrue($this->toDoList->isValid());
    }

    /** 
     * @test 
     */ 
    public function isNametoDoListValid() {
        $this->toDoList->setName("");
        $this->assertTrue($this->toDoList->isValid());
    }

    /** 
     * @test 
     */ 
    public function isContenttoDoListNotNull() {
        $this->toDoList->setDescription("");
        $this->assertTrue($this->toDoList->isValid());
    }
}