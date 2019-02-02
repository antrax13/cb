<?php
/**
 * Created by PhpStorm.
 * User: peterkosak
 * Date: 10/01/2019
 * Time: 16:57
 */

namespace App\Form\CreateStamp;


use App\Entity\StampShape;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class StampShapeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('stampType', EntityType::class,[
                'class' => StampShape::class,
                'placeholder' => 'Please choose',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('isSphere', ChoiceType::class, [
                'label' => 'Is this a Sphere Stamp',
                'choices' => [
                    'Yes' => true,
                    'No' => false
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}