<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NewMealType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('meal_name', TextType::class, [
                'label' => "Qu'est ce que c'est du coup ?"
            ])
            ->add('question', TextType::class, [
                'label' => "Quelle question permet de le différencier de"
            ])
            ->add('yes_no', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => "Quelle est la réponse ?"
            ])
            ->add('submit', SubmitType::class)
        ;
    }
}
