<?php

namespace Tests\AppBundle\Service;

use App\Entity\ToDoList;
use Exception;
use PHPUnit\Framework\TestCase;

class ToDoListServiceTest extends TestCase
{
    protected ToDoList $todo;
    protected ToDoListService $todoService;

    public function setUp(): void
    {
        parent::setUp();
        $this->todo = new TodoListService();
        $this->todo->setFirstName('Ludovic');
    }

    /** 
     * Test la méthode isValid() pour un cas de user valide
     * 
     * @test 
     */
    public function testUserValid()
    {
        $this->assertTrue($this->user->isValid());
    }

    /** 
     * Test la méthode isValid() pour un cas de user non valide : firstName vide
     * 
     * @test 
     */
    public function testFirstNameUserEmpty()
    {
        $this->user->setFirstName("");
        $this->expectException(Exception::class);
        $this->user->isValid();
    }

    /** 
     * Test la méthode isValid() pour un cas de user non valide : lastName vide
     * 
     * @test 
     */
    public function testLastNameUserEmpty()
    {
        $this->user->setFirstName("Ludo");
        $this->user->setLastName("");
        $this->expectException(Exception::class);
        $this->user->isValid();
    }

    /** 
     * Test la méthode isValid() pour un cas de user non valide : email invalide
     * 
     * @test 
     */
    public function testEmailUserNotValid()
    {
        $this->user->setLastName("Collignon");
        $this->user->setEmail("td.com123");
        $this->expectException(Exception::class);
        $this->user->isValid();
    }

    /** 
     * Test la méthode isValid() pour un cas de user non valide : password invalide
     * 
     * @test 
     */
    public function testPasswordUserNotValid()
    {
        $this->user->setEmail("lud.collignon@gmail.com");
        $this->user->setPassword("pwd");
        $this->expectException(Exception::class);
        $this->user->isValid();
    }

    /** 
     * Test la méthode isValid() pour un cas de user valide : password valide
     * 
     * @test 
     */
    public function testPasswordUserValid()
    {
        $this->user->setPassword("sjhqfkheioufgiushdfs3d4f53s54d");
        $this->assertTrue($this->user->isValid());
    }

    /** 
     * Test la méthode isValid() pour un cas de user non valide : âge < 13 ans
     * 
     * @test 
     */
    public function testBirthdayNotValid()
    {
        $this->user->setBirthday(Carbon::now()->subYear(8));
        $this->expectException(Exception::class);
        $this->user->isValid();
    }

    /** 
     * Test la méthode isValid() pour un cas de user valide : âge > 13 ans
     * 
     * @test 
     */
    public function testBirthdayValid()
    {
        $this->user->setBirthday(Carbon::now()->subYear(17));
        $this->assertTrue($this->user->isValid());
    }
}
