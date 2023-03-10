<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Oui', SubmitType::class)
            ->add('Non', SubmitType::class)
            ->add('next_true_question_id', HiddenType::class, ['required' => false])
            ->add('next_false_question_id', HiddenType::class, ['required' => false])
            ->add('id', HiddenType::class, ['required' => false])
        ;
    }
}
