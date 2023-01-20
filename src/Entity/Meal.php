<?php

namespace App\Entity;

use App\Repository\MealRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MealRepository::class)]
class Meal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $last_true_question_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $last_false_question_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastFalseQuestionId(): ?int
    {
        return $this->last_false_question_id;
    }

    public function setLastFalseQuestionId(?int $last_false_question_id): self
    {
        $this->last_false_question_id = $last_false_question_id;

        return $this;
    }

    public function getLastTrueQuestionId(): ?int
    {
        return $this->last_true_question_id;
    }

    public function setLastTrueQuestionId(?int $last_true_question_id): self
    {
        $this->last_true_question_id = $last_true_question_id;

        return $this;
    }
}
