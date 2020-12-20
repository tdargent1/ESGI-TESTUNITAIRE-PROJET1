<?php

namespace App\DataFixtures;

use App\Entity\ToDoList;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ToDoListFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        $object = (new User())
            ->setEmail("dev@technique")
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword(self::PWD)
        ;
        $manager->persist($object);

        $users = $manager->getRepository(User::class)->findAll();

        for ($i=0; $i<10; $i++) {
            shuffle($users);

            $object = (new ToDoList())
                ->setName($faker->name)
                ->setContent($faker->paragraph)
                ->setCreatedAt($faker->dateTime)
                ->setOwner($users)
            ;
            $manager->persist($object);

            if ($i % 5) {
                $manager->flush();
            }
        }

        $manager->flush();
    }
}
