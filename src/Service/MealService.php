<?php

namespace App\Service;

use App\Entity\Meal;
use App\Entity\Question;
use App\Repository\MealRepository;
use App\Repository\QuestionRepository;
use Doctrine\Persistence\ManagerRegistry;

class MealService
{
    private MealRepository $mealRepository;
    private QuestionRepository $questionRepository;
    private ManagerRegistry $doctrine;

    public function __construct(MealRepository $mealRepository, QuestionRepository $questionRepository, ManagerRegistry $doctrine)
    {
        $this->mealRepository = $mealRepository;
        $this->questionRepository = $questionRepository;
        $this->doctrine = $doctrine;
    }

    public function createMeal(Meal $lastMeal, $datas)
    {
        $lastQuestion = $this->questionRepository->findOneBy(['id' => $lastMeal->getLastFalseQuestionId()]);

        if ($lastQuestion === null) {
            $lastQuestion = $this->questionRepository->findOneBy(['id' => $lastMeal->getLastTrueQuestionId()]);
        }

        $newQuestion = new Question();

        $newQuestion->setNode(1);
        
        if ($lastQuestion !== null) {
            $newQuestion->setNode(($lastQuestion->getNode() + 1));
        }

        $newQuestion->setQuestion($datas['question']);

        $entityManager = $this->doctrine->getManager();

        $entityManager->persist($newQuestion);
        $entityManager->flush();
        $entityManager->refresh($newQuestion);

        if ($lastMeal->getLastFalseQuestionId() !== null) {

            $lastQuestion->setNextFalseQuestionId($newQuestion->getId());
            $entityManager->persist($lastQuestion);
            $entityManager->flush();
        }


        if ($lastMeal->getLastTrueQuestionId() !== null) {

            $lastQuestion->setNextTrueQuestionId($newQuestion->getId());
            $entityManager->persist($lastQuestion);
            $entityManager->flush();
        }

        $newMeal = new Meal();

        if ($datas['yes_no'] === true) {
            $newMeal->setLastTrueQuestionId($newQuestion->getId());
            $newMeal->setLastFalseQuestionId(null);

            $lastMeal->setLastFalseQuestionId($newQuestion->getId());
            $lastMeal->setLastTrueQuestionId(null);
        }

        if ($datas['yes_no'] === false) {
            $newMeal->setLastFalseQuestionId($newQuestion->getId());
            $newMeal->setLastTrueQuestionId(null);

            $lastMeal->setLastTrueQuestionId($newQuestion->getId());
            $lastMeal->setLastFalseQuestionId(null);
        }

        $newMeal->setName($datas['meal_name']);

        $entityManager->persist($newMeal);
        $entityManager->flush();
        $entityManager->refresh($newMeal);
        $entityManager->persist($lastMeal);
        $entityManager->flush();
    }

    public function getCountOfAllMeals(): int
    {
        return $this->mealRepository->getCountOfAllMeals();
    }
}