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
        
        $node = 1;
        
        if ($lastQuestion !== null) {
            $node = $lastQuestion->getNode() + 1;
        }

        $newQuestion = $this->createNewQuestion($datas['question'], $node);

        $this->modifyQuestion($lastQuestion, $lastMeal->getLastTrueQuestionId(), $lastMeal->getLastFalseQuestionId(), $newQuestion->getId());

        if ($datas['yes_no'] === true) {

            $this->createNewMeal($datas['meal_name'], $newQuestion->getId(), null);
            $this->modifyMeal($lastMeal, null, $newQuestion->getId());
        }

        if ($datas['yes_no'] === false) {
            
            $this->createNewMeal($datas['meal_name'],null , $newQuestion->getId());
            $this->modifyMeal($lastMeal, $newQuestion->getId(), null);

        }
    }

    public function getCountOfAllMeals(): int
    {
        return $this->mealRepository->getCountOfAllMeals();
    }

    public function getTheStartMeal(): Meal
    {
        $meals = $this->mealRepository->findAll();

        return $meals[0];
    }

    public function existOnlyOneMeal(): bool
    {
        $meals = $this->mealRepository->findAll();

        if (count($meals) === 1) {
            return true;
        }

        return false;
    }

    public function createNewMeal(string $mealName, int|null $newTrueQuestionId, int|null $newFalseQuestionId): void
    {
        $entityManager = $this->doctrine->getManager();

        $newMeal = new Meal();

        $newMeal->setLastFalseQuestionId($newFalseQuestionId);
        $newMeal->setLastTrueQuestionId($newTrueQuestionId);

        $newMeal->setName($mealName);

        $entityManager->persist($newMeal);
        $entityManager->flush();
        $entityManager->refresh($newMeal);
    }

    public function createNewQuestion(string $question, int $node): Question
    {
        $entityManager = $this->doctrine->getManager();
        $newQuestion = new Question();
        
        $newQuestion->setNode($node);

        $newQuestion->setQuestion($question);

        $entityManager->persist($newQuestion);
        $entityManager->flush();
        $entityManager->refresh($newQuestion);

        return $newQuestion;
    }

    public function modifyMeal(Meal $lastMeal, int|null $lastTrueQuestionId, int|null $lastFalseQuestionId)
    {
        $entityManager = $this->doctrine->getManager();

        $lastMeal->setLastTrueQuestionId($lastTrueQuestionId);
        $lastMeal->setLastFalseQuestionId($lastFalseQuestionId);

        $entityManager->persist($lastMeal);
        $entityManager->flush();
    }

    public function modifyQuestion(Question|null $lastQuestion, int|null $lastTrueQuestionId, int|null $lastFalseQuestionId, int|null $newQuestionId): void
    {
        $entityManager = $this->doctrine->getManager();

        if ($lastFalseQuestionId !== null) {

            $lastQuestion->setNextFalseQuestionId($newQuestionId);
            $entityManager->persist($lastQuestion);
            $entityManager->flush();
        }

        if ($lastTrueQuestionId !== null) {

            $lastQuestion->setNextTrueQuestionId($newQuestionId);
            $entityManager->persist($lastQuestion);
            $entityManager->flush();
        }
    }
}