<?php
/**
 * Created by PhpStorm.
 * User: peterkosak
 * Date: 10/01/2019
 * Time: 17:11
 */

namespace App\Form\CreateStamp;


use App\Entity\HandleColor;
use App\Entity\HandleShape;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class HandleSelection extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('handleShape', EntityType::class,[
                'class' => HandleShape::class,
                'placeholder' => 'Please choose',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('handleColor', EntityType::class,[
                'class' => HandleColor::class,
                'placeholder' => 'Please choose',
                'constraints' => [
                    new NotBlank(),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}