<?php

namespace Tests\AppBundle\Entity;

use App\Entity\ToDoList;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ToDoListTest extends TestCase
{
    protected $toDoList;

    public function setUp(): void
    {
        parent::setUp();
        
        // $this->toDoList = new ToDoList($user);
        // $this->toDoList->setName("La numéro uno");
        // $this->toDoList->setDescription("Ma première TODOLIST");
    }

    /** 
     * @test 
     */ 
    public function testIstoDoListValid() {
        $this->assertTrue($this->toDoList->isValid());
    }

    /** 
     * @test 
     */ 
    public function testIsNametoDoListValid() {
        $this->toDoList->setName("");
        $this->assertTrue($this->toDoList->isValid());
    }

    /** 
     * @test 
     */ 
    public function testIsContenttoDoListNotNull() {
        $this->toDoList->setDescription("");
        $this->assertTrue($this->toDoList->isValid());
    }
}