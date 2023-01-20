<?php

namespace App\DataFixtures;

use App\Entity\Meal;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $meal = new Meal();
        $meal->setName('Une pizza');
        $manager->persist($meal);

        $manager->flush();
    }
}
