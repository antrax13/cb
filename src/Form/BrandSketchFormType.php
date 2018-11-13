<?php

namespace App\Form;

use App\Entity\BrandSketch;
use App\Entity\StampType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BrandSketchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file',FileType::class,[

            ])
            ->add('weight', null, [
                'help' => 'also include unit'
            ])
            ->add('price', null, [
                'help' => 'price is in pounds (£)'
            ])
            ->add('stampType', EntityType::class,[
                'class' => StampType::class,
                'placeholder' => 'Please choose',
            ])
            ->add('dimension', null, [
                'help' => 'also include units'
            ])
            ->add('note', null, [
                'help' => 'special requirements, notes, descriptions'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BrandSketch::class,
        ]);
    }
}
