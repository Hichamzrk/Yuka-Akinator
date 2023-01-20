<?php

namespace App\Controller;

use App\Form\QuestionType;
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
    ) {}

    #[Route('/', name: 'search_meal')]
    public function index(Request $request): Response
    {
        $questionForm = $this->createForm(QuestionType::class);

        $meals = $this->mealRepository->findAll();

        if (count($meals) === 1) {
            return $this->redirectToRoute('meal_found', ['id' => $meals[0]->getId()]);
        }
        
        $question = $this->questionRepository->findAll()[0];

        $questionForm->get('next_true_question_id')->setData($question->getNextTrueQuestionId());
        $questionForm->get('next_false_question_id')->setData($question->getNextFalseQuestionId());
        $questionForm->get('id')->setData($question->getId());

        $questionForm->handleRequest($request);

        if ($questionForm->isSubmitted() && $questionForm->isValid()) {

            if($questionForm->get('Oui')->isClicked()){
                if ($questionForm->get('next_true_question_id')->getData() === null) {

                    $meal = $this->mealRepository->findOneBy([
                        'last_true_question_id' => $questionForm->get('id')->getData()
                    ]);
                    
                    return $this->redirectToRoute('meal_found', ['id' => $meal->getId()]);
                }

                if ($this->questionRepository->findOneBy(['id' => $questionForm->get('next_true_question_id')->getData()])) {
                    $question = $this->questionRepository->findOneBy([
                        'id' => $questionForm->get('next_true_question_id')->getData()
                    ]);
                }
            }

            if($questionForm->get('Non')->isClicked()){
                if ($questionForm->get('next_false_question_id')->getData() === null) {
                    
                    $meal = $this->mealRepository->findOneBy([
                        'last_false_question_id' => $questionForm->get('id')->getData()
                    ]);

                    return $this->redirectToRoute('meal_found', ['id' => $meal->getId()]);
                }
                
                if ($this->questionRepository->findOneBy(['id' => $questionForm->get('next_false_question_id')->getData()])) {
                    $question = $this->questionRepository->findOneBy([
                        'id' => $questionForm->get('next_false_question_id')->getData()
                    ]);
                }
            }
        }

        $questionForm = $this->createForm(QuestionType::class);

        $questionForm->get('next_true_question_id')->setData($question->getNextTrueQuestionId());
        $questionForm->get('next_false_question_id')->setData($question->getNextFalseQuestionId());
        $questionForm->get('id')->setData($question->getId());

        $maxNode = $this->questionRepository->getMaxNode();
        $countOfMeals = $this->mealRepository->getCountOfAllMeals();

        return $this->render('/question/questions.html.twig', [
            'form' => $questionForm,
            'question' => $question->getQuestion(),
            'maxNode' => $maxNode,
            'currentNode' => $question->getNode(),
            'countOfMeals' => $countOfMeals
        ]); 
    }
}
