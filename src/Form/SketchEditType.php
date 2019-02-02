<?php

namespace App\Form;

use App\Entity\BrandSketch;
use App\Entity\StampShape;
use App\Entity\StampType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SketchEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'help' => 'will be used for search',
                'label' => 'Logo description'
            ])
            ->add('newFile', FileType::class, [
                'mapped' => false,
                'help' => 'this file will overwrite the current file'
            ])
            ->add('stampType', EntityType::class,[
                'class' => StampType::class,
                'placeholder' => 'Please choose',
            ])
            ->add('weight', null, [
                'help' => 'in kilograms (kg)'
            ])
            ->add('price', null, [
                'help' => 'price in pounds (£)'
            ])
            ->add('qty', null, [
                'label' => 'Quantity'
            ])
            ->add('stampShape', EntityType::class,[
                'class' => StampShape::class,
                'placeholder' => 'Please choose',
            ])
            ->add('dimension', null, [
                'help' => 'also include units'
            ])
            ->add('handle', null, [

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
