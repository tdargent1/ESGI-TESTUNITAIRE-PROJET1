<?php

use Carbon\Carbon;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->initUser(
            'Ludovic', 
            'Collignon', 
            'lud@gmail.com', 
            'password123', 
            Carbon::createFromFormat('Y-m-d', '1998-06-21')
        );
    }

    public function initUser($firstName, $lastName, $email, $password, $birthday) {
        $this->user->setFirstName($firstName);
        $this->user->setLastName($lastName);
        $this->user->setEmail($email);
        $this->user->setPassword($password);
        $this->user->setBirthday($birthday);
    }

    /** 
     * @test 
     */ 
    public function isUserValid() {
        $this->assertTrue($this->user->isValid());
    }

    /** 
     * @test 
     */ 
    public function isFirstNameUserValid() {
        $this->user->setFirstName("");
        $this->assertTrue($this->user->isValid());
    }

    /** 
     * @test 
     */ 
    public function isLastNameUserValid() {
        $this->user->setLastName("");
        $this->assertTrue($this->user->isValid());
    }

    /** 
     * @test 
     */ 
    public function isEmailUserValid() {
        $this->user->setEmail("td.com123");
        $this->assertTrue($this->user->isValid());
    }

    /** 
     * @test 
     */ 
    public function isPasswordUserValid() {
        $this->user->setPassword("pwd");
        $this->assertTrue($this->user->isValid());
    }

    /** 
     * @test 
     */ 
    public function isBirthdayUserValid() {
        $this->user->setBirthday(Carbon::now());
        $this->assertTrue($this->user->isValid());
    }
}