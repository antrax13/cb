<?php

namespace App\Form\Admin;

use App\Entity\FaqCategory;
use App\Entity\FaqQuestion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateFaqFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('faqCategory', EntityType::class, [
                'placeholder' => 'Please choose',
                'class' => FaqCategory::class,
            ])
            ->add('question', null, [
                'attr' => [
                    'rows' => 4,
                ]
            ])
            ->add('answer', null ,[
                'attr' => [
                    'rows' => 4,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FaqQuestion::class,
        ]);
    }
}
