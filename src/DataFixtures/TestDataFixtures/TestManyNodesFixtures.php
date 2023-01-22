<?php

namespace App\DataFixtures\TestDataFixtures;

use App\Entity\Meal;
use App\Entity\Question;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class TestManyNodesFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $meal = new Meal();
        $meal->setName('Une pizza');
        $manager->persist($meal);

        $meal = new Meal();
        $meal->setName('Une Glace');
        $manager->persist($meal);

        $meal = new Meal();
        $meal->setName('Fromage');
        $manager->persist($meal);

        $question1 = new question();
        $question1->setQuestion("Est-ce que c'est froid");
        $question1->setNode(1);
        $manager->persist($question1);
        $manager->flush();
        $manager->refresh($question1);
        
        $question2 = new question();
        $question2->setQuestion("Est-ce que c'est froid");
        $question2->setNode(2);
        $manager->persist($question2);
        $manager->flush();
        $manager->refresh($question2);

        $question1->setNextFalseQuestionId($question2->getId());
        $manager->persist($question1);
        $manager->flush();

        $question3 = new question();
        $question3->setQuestion("Est-ce que c'est froid");
        $question3->setNode(3);
        $manager->persist($question3);
        $manager->flush();
        $manager->refresh($question3);

        $question2->setNextFalseQuestionId($question3->getId());
        $manager->flush();
        $manager->refresh($question2);

    }

    public static function getGroups(): array
    {
        return ['test'];
    }
}
