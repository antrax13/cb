<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Customer;
use App\Entity\Quote;
use App\Entity\Shipping;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuoteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer', EntityType::class,[
                'class' => Customer::class,
                'placeholder' => 'Please choose',
            ])
            ->add('request')
            ->add('answer')
            ->add('shippingCountry', EntityType::class,[
                'class' => Country::class,
                'placeholder' => 'Please choose',
            ])
            ->add('status', ChoiceType::class,[
                'placeholder' => 'Please choose',
                'choices' => [
                    'Draft' => 'Draft',
                    'PDF Generated' => 'PDF Generated',
                    'Email Sent' => 'Email Sent',
                    'Invoice Sent' => 'Invoice Sent',
                    'Cancel' => 'Cancel'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quote::class,
        ]);
    }
}
