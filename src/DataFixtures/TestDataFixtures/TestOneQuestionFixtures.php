<?php

namespace App\DataFixtures\TestDataFixtures;

use App\Entity\Meal;
use App\Entity\Question;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class TestOneQuestionFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $question = new question();
        $question->setQuestion("Est-ce que c'est froid");
        $question->setNode(1);
        $manager->persist($question);
        $manager->flush();
        $manager->refresh($question);

        $meal = new Meal();
        $meal->setName('Une pizza');
        $meal->setLastFalseQuestionId($question->getId());
        $manager->persist($meal);
        $manager->flush();

        $meal = new Meal();
        $meal->setName('Une glace');
        $meal->setLastTrueQuestionId($question->getId());
        $manager->persist($meal);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['test'];
    }
}
