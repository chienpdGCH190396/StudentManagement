<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StudentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 10; $i++) {
            $student = new Student();
            $student->setName("Student $i");
            $student->setDob(\DateTime::createFromFormat('Y-m-d','2021-10-20'));
            $student->setEmail("student$i@gmail.com");
            $student->setImage("img_avatar.png");
            $manager->persist($student);
        }

        $manager->flush();
    }
}
