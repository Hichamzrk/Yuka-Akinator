<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Form\NewMealType;
use App\Repository\MealRepository;
use App\Repository\QuestionRepository;
use App\Service\MealService;
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
        public ManagerRegistry $doctrine,
        public MealService $mealService
    ) {}

    #[Route('/createMeal/{id}', name: 'create_meal')]
    public function createMeal(Meal $lastMeal, Request $request): Response
    {
        $form = $this->createForm(NewMealType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();

            $this->mealService->createMeal($lastMeal, $datas);

            return $this->render('transitionPage.html.twig');
        }

        return $this->render('/meal/newMeal.html.twig', [
            'form' => $form,
            'mealName' => $lastMeal->getName()
        ]);
    }
}