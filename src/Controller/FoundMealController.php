<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Form\QuestionType;
use App\Repository\MealRepository;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FoundMealController extends AbstractController
{
    public function __construct
    (
        public MealRepository $mealRepository,
        public QuestionRepository $questionRepository,
    ) {}

    #[Route('/mealFound/{id}', name: 'meal_found')]
    public function mealFound(Meal $meal, Request $request): Response
    {    
        $mealForm = $this->createForm(QuestionType::class);
        $mealForm->handleRequest($request);

        if ($mealForm->isSubmitted() && $mealForm->isValid()) {
            if($mealForm->get('Oui')->isClicked()){
                return $this->render('/meal/congratulations.html.twig', [
                    'mealName' => $meal->getName()
                ]); 
            }

            if($mealForm->get('Non')->isClicked()){
                return $this->redirectToRoute('create_meal', ['id' => $meal->getId()]);
            }
        }

        $countOfMeals = $this->mealRepository->getCountOfAllMeals();

        return $this->render('/meal/mealFound.html.twig', [
            'form' => $mealForm,
            'mealName' => $meal->getName(),
            'countOfMeals' => $countOfMeals
        ]); 
    }
}