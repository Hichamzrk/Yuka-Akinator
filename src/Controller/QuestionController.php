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
        $meals = $this->mealRepository->findAll();
        
        if (count($meals) === 1) {
            return $this->redirectToRoute('meal_found', ['id' => $meals[0]->getId()]);
        }
        
        $question = $this->questionRepository->findAll()[0];
        
        $form = $this->createForm(QuestionType::class);
        $questionForm = $this->questionService->handleForm($form, $question)
                                              ->handleRequest($request);

        if ($questionForm->isSubmitted() && $questionForm->isValid()) {

            $datas = $this->questionService->searchMeal($questionForm);
            
            if (get_class($datas) === 'App\Entity\Meal') {
                return $this->redirectToRoute('meal_found', ['id' => $datas->getId()]);
            }

            if (get_class($datas) === 'App\Entity\Question') {
                $question = $datas;
            }
        }

        $form = $this->createForm(QuestionType::class);
        $questionForm = $this->questionService->handleForm($form, $question);

        $maxNode = $this->questionService->getMaxNode();
        $countOfMeals = $this->mealService->getCountOfAllMeals();

        return $this->render('/question/questions.html.twig', [
            'form' => $questionForm,
            'question' => $question->getQuestion(),
            'numberOfNodesRemaining' => ($maxNode - $question->getNode() + 1),
            'countOfMeals' => $countOfMeals
        ]); 
    }
}