<?php

namespace App\Service;

use App\Entity\Meal;
use App\Entity\Question;
use App\Repository\MealRepository;
use App\Repository\QuestionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;

class QuestionService
{
    public function __construct
    (   
        public MealRepository $mealRepository,
        public QuestionRepository $questionRepository,
        private ManagerRegistry $doctrine,
    ) {}
    
    public function searchNextQuestion(FormInterface $questionForm): Question|Meal
    {
        $isTrue = $questionForm->get('Oui')->isClicked();
        $nextQuestionId = $isTrue ? $questionForm->get('next_true_question_id')->getData() : $questionForm->get('next_false_question_id')->getData();

        if ($nextQuestionId === null) {
            $lastQuestionId = $isTrue ? 'last_true_question_id' : 'last_false_question_id';
            $meal = $this->mealRepository->findOneBy([
                $lastQuestionId => $questionForm->get('id')->getData()
            ]);
            return $meal;
        }

        if ($question = $this->questionRepository->findOneBy(['id' => $nextQuestionId])) {
            return $question;
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
}
