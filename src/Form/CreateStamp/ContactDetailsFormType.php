<?php

namespace App\Form\CreateStamp;

use App\Entity\Country;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactDetailsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', null, [
                'label' => 'Your full name',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'invalid_message' => 'The email fields must match.',
                'required' => true,
                'first_options'  => [
                    'label' => 'Email'
                ],
                'second_options' => [
                    'label' => 'Repeat Email'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Email()
                ],
                'help' => 'used for communication regarding your quote'
            ])
            ->add('shippingCountry', EntityType::class, [
                'class' => Country::class,
                'placeholder' => 'Please choose',
                'label' => 'Shipping Destination',
                'constraints' => [
                    new NotBlank(),
                ],
                'help' => 'used to estimate price of shipping'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
