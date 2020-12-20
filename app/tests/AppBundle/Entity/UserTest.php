<?php

namespace Tests\AppBundle\Entity;

use Exception;
use Carbon\Carbon;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = new User();
        $this->user->setFirstName('Ludovic');
        $this->user->setLastName('Collignon');
        $this->user->setEmail('lud@gmail.com');
        $this->user->setPassword('password123');
        $this->user->setBirthday(Carbon::create(1998, 6, 21));
    }

    /** 
     * Test la méthode isValid() pour un cas de user valide
     * 
     * @test 
     */ 
    public function testUserValid() {
        $this->assertEmpty($this->user->isValid());
    }

    /** 
     * Test la méthode isValid() pour un cas de user non valide : firstName vide
     * 
     * @test 
     */ 
    public function testFirstNameUserEmpty() {
        $this->user->setFirstName("");
        $this->assertNotEmpty($this->user->isValid());
    }

    /** 
     * Test la méthode isValid() pour un cas de user non valide : lastName vide
     * 
     * @test 
     */ 
    public function testLastNameUserEmpty() {
        $this->user->setFirstName("Ludo");
        $this->user->setLastName("");
        $this->assertNotEmpty($this->user->isValid());
    }

    /** 
     * Test la méthode isValid() pour un cas de user non valide : email invalide
     * 
     * @test 
     */ 
    public function testEmailUserNotValid() {
        $this->user->setLastName("Collignon");
        $this->user->setEmail("td.com123");
        $this->assertNotEmpty($this->user->isValid());
    }

    /** 
     * Test la méthode isValid() pour un cas de user non valide : password invalide
     * 
     * @test 
     */ 
    public function testPasswordUserNotValid() {
        $this->user->setEmail("lud.collignon@gmail.com");
        $this->user->setPassword("pwd");        
        $this->assertNotEmpty($this->user->isValid());
    }

    /** 
     * Test la méthode isValid() pour un cas de user valide : password valide
     * 
     * @test 
     */ 
    public function testPasswordUserValid() {
        $this->user->setPassword("sjhqfkheioufgiushdfs3d4f53s54d");
        $this->assertEmpty($this->user->isValid());
    }

    /** 
     * Test la méthode isValid() pour un cas de user non valide : âge < 13 ans
     * 
     * @test 
     */ 
    public function testBirthdayNotValid() {
        $this->user->setBirthday(Carbon::now()->subYear(8));
        $this->assertNotEmpty($this->user->isValid());
    }

    /** 
     * Test la méthode isValid() pour un cas de user valide : âge > 13 ans
     * 
     * @test 
     */ 
    public function testBirthdayValid() {
        $this->user->setBirthday(Carbon::now()->subYear(17));
        $this->assertEmpty($this->user->isValid());

    }
}