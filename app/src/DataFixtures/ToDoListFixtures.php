<?php

namespace App\DataFixtures;

use App\Entity\ToDoList;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class ToDoListFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference(UserFixtures::USER_REFERENCE);

        $tdl = (new ToDoList($user))
            ->setName("nom")
            ->setDescription("description");

        $manager->persist($tdl);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
