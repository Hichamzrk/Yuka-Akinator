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

    public function getMaxNode(int $questionNodeId): Int
    {
        $longestBranch = 1;
        $questionNode = $this->questionRepository->findOneBy(['id' => $questionNodeId]);

        $nextTrueQuestionId = $questionNode->getNextTrueQuestionId();
        $nextFalseQuestionId = $questionNode->getNextFalseQuestionId();
        if ($nextTrueQuestionId != null) {
            $longestBranch = max($longestBranch, $this->getMaxNode($nextTrueQuestionId) + 1);
        }
        if ($nextFalseQuestionId != null) {
            $longestBranch = max($longestBranch, $this->getMaxNode($nextFalseQuestionId) + 1);
        }

        return $longestBranch;
    }

    public function handleForm(FormInterface $questionForm, Question $question): FormInterface
    {
        $questionForm->get('next_true_question_id')->setData($question->getNextTrueQuestionId());
        $questionForm->get('next_false_question_id')->setData($question->getNextFalseQuestionId());
        $questionForm->get('id')->setData($question->getId());

        return $questionForm;
    }

    public function getFirstQuestion(): Question
    {
        $question = $this->questionRepository->findStartQuestion();

        return $question;
    }

    public function isTheLastQuestion($datas): bool
    {
        if (get_class($datas) === 'App\Entity\Meal') {
            return true;
        }
        
        return false;
    }
}
