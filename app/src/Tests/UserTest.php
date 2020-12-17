<?php

use Carbon\Carbon;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertTrue;

class UserTest extends TestCase
{
    
    private $user;

    public function initUser($firstName, $lastName, $email, $password, $birthday) {
        $this->user->setFirstName($firstName);
        $this->user->setLastName($lastName);
        $this->user->setEmail($email);
        $this->user->setPassword($password);
        $this->user->setBirthday($birthday);
    }

    public function isUserValid() {

        $this->initUser('Ludovic', 'Collignon', 'lud@gmail.com', 'azeazeazpeoapoziepoaizpe', Carbon::createFromFormat('Y-m-d', '1998-06-21'));
        assertTrue($this->user->isValid());

    }

}