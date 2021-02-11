<?php

namespace App\DataFixtures;

use Carbon\Carbon;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';

    public function load(ObjectManager $manager)
    {
        $user = (new User())
            ->setFirstName('Ludovic')
            ->setLastName('Collignon')
            ->setEmail('lud@gmail.com')
            ->setPassword('password123')
            ->setBirthday(Carbon::create(1998, 6, 21));
            
        $manager->persist($user);
        $this->addReference(self::USER_REFERENCE, $user);
        $manager->flush();
    }
}
