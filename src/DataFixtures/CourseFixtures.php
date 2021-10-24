<?php

namespace App\DataFixtures;

use App\Entity\Course;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CourseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i = 1; $i<=10; $i++){
            $course = new Course();
            $course->setName("course $i");
            $course->setType("IT");
            $course->setDescription("This is course $i");
            $manager->persist($course);
        }
        $manager->flush();
    }
}
