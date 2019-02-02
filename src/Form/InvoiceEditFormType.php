<?php

namespace App\Form;

use App\Entity\Invoice;
use App\Entity\Quote;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InvoiceEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quote', EntityType::class, [
                'class' => Quote::class,
                'placeholder' => 'Please select',
                'label' => 'Quote',
                'help' => 'Provide Quote Number',
                'required' => false,
            ])
            ->add('vat',null, [
                'label' => 'VAT in Percentage',
                'help' => 'IE 20.00 for 20% VAT (No VAT enter 0)',
            ])
            ->add('billingAddressFirst', null, [
                'label' => 'Highlighted Billing Address',
                'help' => 'first row of billing address, field will be highlighted on invoice'
            ])
            ->add('billingAddress', null, [
                'attr' => [
                    'rows' => 5,
                ],
            ])
            ->add('invoicedVat', null, [
                'label' => 'Invoiced Company VAT Number',
                'help' => 'VAT number of billing company [field is not required]',
            ])
            ->add('invoicedPhone', null, [
                'label' => 'Invoiced Company Phone Number'
            ])
            ->add('shippingAddress', null, [
                'attr' => [
                    'rows' => 5,
                ]
            ])
            ->add('shippingPhone', null, [
                'label' => 'Shipping Phone Number',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
