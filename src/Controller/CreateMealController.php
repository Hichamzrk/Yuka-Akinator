<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Entity\Question;
use App\Form\NewMealType;
use App\Form\QuestionType;
use App\Repository\MealRepository;
use App\Repository\QuestionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreateMealController extends AbstractController
{
    public function __construct
    (   
        public MealRepository $mealRepository,
        public QuestionRepository $questionRepository,
        public ManagerRegistry $doctrine
    ) {}

    #[Route('/createMeal/{id}', name: 'create_meal')]
    public function createMeal(Meal $lastMeal, Request $request): Response
    {
        $form = $this->createForm(NewMealType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            
            $lastQuestion = $this->questionRepository->findOneBy(['id' => $lastMeal->getLastFalseQuestionId()]);

            if ($lastQuestion === null) {
                $lastQuestion = $this->questionRepository->findOneBy(['id' => $lastMeal->getLastTrueQuestionId()]);
            }

            $newQuestion = new Question();

            if ($lastQuestion === null) {
                $newQuestion->setNode(1);
            }else{
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
            
            return $this->render('savePage.html.twig');
        }
        
        return $this->render('/meal/newMeal.html.twig', [
            'form' => $form,
            'mealName' => $lastMeal->getName()
        ]);

    }
}