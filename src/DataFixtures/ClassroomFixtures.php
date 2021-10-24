<?php

namespace App\DataFixtures;

use App\Entity\Classroom;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClassroomFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i = 1; $i<=10; $i++){
            $classroom = new Classroom();
            $classroom->setName("Classroom $i");
            $classroom->setType("IT");
            $classroom->setDescription("This is Classroom $i");
            $manager->persist($classroom);
        }

        $manager->flush();
    }
}
