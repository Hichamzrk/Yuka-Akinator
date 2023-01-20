<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $question = null;

    #[ORM\Column(nullable: true)]
    private ?int $next_true_question_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $next_false_question_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $node = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(?string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getNextTrueQuestionId(): ?int
    {
        return $this->next_true_question_id;
    }

    public function setNextTrueQuestionId(?int $next_true_question_id): self
    {
        $this->next_true_question_id = $next_true_question_id;

        return $this;
    }

    public function getNextFalseQuestionId(): ?int
    {
        return $this->next_false_question_id;
    }

    public function setNextFalseQuestionId(?int $next_false_question_id): self
    {
        $this->next_false_question_id = $next_false_question_id;

        return $this;
    }

    public function getNode(): ?int
    {
        return $this->node;
    }

    public function setNode(?int $node): self
    {
        $this->node = $node;

        return $this;
    }
}
