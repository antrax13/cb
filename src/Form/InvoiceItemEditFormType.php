<?php

namespace App\Form;

use App\Entity\InvoiceItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InvoiceItemEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('name', null, [
                'label' => 'Item Description',
                'help' => 'name to be visible on invoice',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('price', null, [
                'help' => 'price in pounds (€)',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('qty', null, [
                'label' => 'Quantity',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('weightPerItem', null, [
                'label' => 'Weight Per 1 Item',
                'help' => 'in kilograms (kg)',
                'constraints' => [
                    new NotBlank()
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}
