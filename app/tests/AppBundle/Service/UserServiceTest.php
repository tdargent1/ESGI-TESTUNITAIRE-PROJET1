<?php

namespace Tests\AppBundle\Service;

use Carbon\Carbon;
use App\Entity\User;
use App\Service\UserService;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;

class UserServiceTest extends TestCase
{
    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $this->userService = new UserService($entityManager);

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
        $this->assertEmpty($this->userService->isValid($this->user));
    }

    /** 
     * Test la méthode isValid() pour un cas de user non valide : firstName vide
     * 
     * @test 
     */ 
    public function testFirstNameUserEmpty() {
        $this->user->setFirstName("");
        $this->assertNotEmpty($this->userService->isValid($this->user));
    }

    /** 
     * Test la méthode isValid() pour un cas de user non valide : lastName vide
     * 
     * @test 
     */ 
    public function testLastNameUserEmpty() {
        $this->user->setFirstName("Ludo");
        $this->user->setLastName("");
        $this->assertNotEmpty($this->userService->isValid($this->user));
    }

    /** 
     * Test la méthode isValid() pour un cas de user non valide : email invalide
     * 
     * @test 
     */ 
    public function testEmailUserNotValid() {
        $this->user->setLastName("Collignon");
        $this->user->setEmail("td.com123");
        $this->assertNotEmpty($this->userService->isValid($this->user));
    }

    /** 
     * Test la méthode isValid() pour un cas de user non valide : password invalide
     * 
     * @test 
     */ 
    public function testPasswordUserNotValid() {
        $this->user->setEmail("lud.collignon@gmail.com");
        $this->user->setPassword("pwd");        
        $this->assertNotEmpty($this->userService->isValid($this->user));
    }

    /** 
     * Test la méthode isValid() pour un cas de user valide : password valide
     * 
     * @test 
     */ 
    public function testPasswordUserValid() {
        $this->user->setPassword("sjhqfkheioufgiushdfs3d4f53s54d");
        $this->assertEmpty($this->userService->isValid($this->user));
    }

    /** 
     * Test la méthode isValid() pour un cas de user non valide : âge < 13 ans
     * 
     * @test 
     */ 
    public function testBirthdayNotValid() {
        $this->user->setBirthday(Carbon::now()->subYear(8));
        $this->assertNotEmpty($this->userService->isValid($this->user));
    }

    /** 
     * Test la méthode isValid() pour un cas de user valide : âge > 13 ans
     * 
     * @test 
     */ 
    public function testBirthdayValid() {
        $this->user->setBirthday(Carbon::now()->subYear(17));
        $this->assertEmpty($this->userService->isValid($this->user));

    }
}