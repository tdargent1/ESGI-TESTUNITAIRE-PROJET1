<?php

namespace Tests\AppBundle\Entity;

use Carbon\Carbon;
use App\Entity\User;
use App\Entity\ToDoList;
use PHPUnit\Framework\TestCase;

class ToDoListTest extends TestCase
{
    protected $toDoList;

    public function setUp(): void
    {
        parent::setUp();
        
        $user = $this->createMock(User::class);
        
        $this->toDoList = new ToDoList($user);
        $this->toDoList->setName("La numéro uno");
        $this->toDoList->setDescription("Ma première TODOLIST");
    }

    /** 
     * Vérifie que la todolist est valide
     * 
     * @test 
     */ 
    public function testIsToDoListValid() {
        $this->assertEmpty($this->toDoList->isValid());
    }

    /** 
     * Vérifie que le nom de la ToDoList est empty
     * 
     * @test 
     */ 
    public function testNameToDoListEmpty() {
        $this->toDoList->setName("");
        $this->assertNotEmpty($this->toDoList->isValid());
    }

    /** 
     * Vérifie que la description de la ToDoList est empty
     * 
     * @test 
     */ 
    public function testDescriptionToDoListEmpty() {
        $this->toDoList->setDescription("");
        $this->assertNotEmpty($this->toDoList->isValid());
    }
}