<?php

namespace App\Controller;

use App\Form\QuestionType;
use App\Service\MealService;
use App\Service\QuestionService;
use App\Repository\MealRepository;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QuestionController extends AbstractController
{
    public function __construct
    (
        public MealRepository $mealRepository,
        public QuestionRepository $questionRepository,
        public QuestionService $questionService,
        public MealService $mealService
    ) {}

    #[Route('/', name: 'search_meal')]
    public function index(Request $request): Response
    {
        if ($this->mealService->existOnlyOneMeal()) {
            return $this->redirectToRoute('meal_found', [
                'id' => $this->mealService->getTheStartMeal()->getId()
            ]);
        }

        $question = $this->questionService->getFirstQuestion();;
        
        $form = $this->createForm(QuestionType::class);
        $questionForm = $this->questionService->handleForm($form, $question)
                                              ->handleRequest($request);

        if ($questionForm->isSubmitted() && $questionForm->isValid()) {

            $datas = $this->questionService->searchMeal($questionForm);

            if ($this->questionService->isTheLastQuestion($datas)) {
                return $this->redirectToRoute('meal_found', ['id' => $datas->getId()]);
            }

            $question = $datas;
        }

        $form = $this->createForm(QuestionType::class);
        $questionForm = $this->questionService->handleForm($form, $question);

        $numberOfNodesRemaining = $this->questionService->getMaxNode($question->getId());
        $countOfMeals = $this->mealService->getCountOfAllMeals();

        return $this->render('/question/questions.html.twig', [
            'form' => $questionForm,
            'question' => $question->getQuestion(),
            'numberOfNodesRemaining' => $numberOfNodesRemaining,
            'countOfMeals' => $countOfMeals
        ]); 
    }
}