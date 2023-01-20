<?php

namespace App\Service;

use App\Entity\Meal;
use App\Entity\Question;
use App\Repository\MealRepository;
use App\Repository\QuestionRepository;
use Symfony\Component\Form\FormInterface;

class QuestionService
{
    public function __construct
    (   
        public MealRepository $mealRepository,
        public QuestionRepository $questionRepository,
    ) {}
    
    public function searchMeal(FormInterface $questionForm): Question|Meal
    {
        if($questionForm->get('Oui')->isClicked()){
            if ($questionForm->get('next_true_question_id')->getData() === null) {

                $meal = $this->mealRepository->findOneBy([
                    'last_true_question_id' => $questionForm->get('id')->getData()
                ]);
                
                return $meal;
            }

            if ($this->questionRepository->findOneBy(['id' => $questionForm->get('next_true_question_id')->getData()])) {
                $question = $this->questionRepository->findOneBy([
                    'id' => $questionForm->get('next_true_question_id')->getData()
                ]);

                return $question;
            }
        }

        if($questionForm->get('Non')->isClicked()){
            if ($questionForm->get('next_false_question_id')->getData() === null) {
                
                $meal = $this->mealRepository->findOneBy([
                    'last_false_question_id' => $questionForm->get('id')->getData()
                ]);

                return $meal;
            }

            if ($this->questionRepository->findOneBy(['id' => $questionForm->get('next_false_question_id')->getData()])) {
                $question = $this->questionRepository->findOneBy([
                    'id' => $questionForm->get('next_false_question_id')->getData()
                ]);

                return $question;
            }
        }
    }

    public function getMaxNode(): Int
    {
        return $this->questionRepository->getMaxNode();
    }

    public function handleForm(FormInterface $questionForm, Question $question): FormInterface
    {
        $questionForm->get('next_true_question_id')->setData($question->getNextTrueQuestionId());
        $questionForm->get('next_false_question_id')->setData($question->getNextFalseQuestionId());
        $questionForm->get('id')->setData($question->getId());

        return $questionForm;
    }
}
