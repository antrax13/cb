<?php

namespace App\Form\Admin;

use App\Entity\CustomProductInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomProductInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type')
            ->add('image', FileType::class, [

            ])
            ->add('intro',null, [
                'attr' => [
                    'rows' => 3,
                ]
            ])
            ->add('details', null, [
                'attr' => [
                    'rows' => 20,
                ]
            ])
            ->add('isFeatured', ChoiceType::class,[
                'label' => 'Make this product visible?',
                'choices' => [
                    'Yes' => true,
                    'No' => false
                ]
            ])
            ->add('fetchOrder')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CustomProductInfo::class,
        ]);
    }
}
