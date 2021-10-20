<?php

namespace App\DataFixtures;
use App\Entity\Teacher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeacherFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i = 1; $i <= 5; $i++) {
            $teacher = new Teacher();
            $teacher->setName("Teacher $i");
            $teacher->setDob(\DateTime::createFromFormat('Y-m-d','2021-10-20'));
            $teacher->setEmail("teacher$i@gmail.com");
            $teacher->setImage("img_avatar.png");
            $manager->persist($teacher);
        }

        $manager->flush();
    }
}
