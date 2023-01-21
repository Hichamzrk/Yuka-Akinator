<?php

namespace App\DataFixtures\DevDataFixtures;

use App\Entity\Meal;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $meal = new Meal();
        $meal->setName('Une pizza');
        $manager->persist($meal);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}
