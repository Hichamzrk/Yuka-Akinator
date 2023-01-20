<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
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
                'label' => "Qu'elle question permet de différenciéer de"
            ])
            ->add('yes_no', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => "Qu'elle est la réponse ?"
            ])
            ->add('submit', SubmitType::class)
        ;
    }
}
