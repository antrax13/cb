<?php

namespace App\Form;

use App\Entity\InvoiceItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InvoiceItemNewFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Item Description',
                'help' => 'name to be visible on invoice'
            ])
            ->add('price', null, [
                'help' => 'price in pounds (£)'
            ])
            ->add('qty', null, [
                'label' => 'Quantity'
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
            'data_class' => InvoiceItem::class,
        ]);
    }
}
