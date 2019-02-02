<?php
/**
 * Created by PhpStorm.
 * User: peterkosak
 * Date: 10/01/2019
 * Time: 16:44
 */

namespace App\Form\CreateStamp;

use App\Entity\HandleColor;
use App\Entity\HandleShape;
use App\Entity\StampShape;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class IceStampCustomFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file',FileType::class,[
                'constraints' => [
                    new NotBlank(),
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            "application/pdf",
                            "image/jpeg",
                            "image/bmp",
                            "image/png"
                        ]
                    ])
                ]
            ])
            ->add('qty', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan(0)
                ]
            ])
            ->add('stamp_shape', null, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('handle_shape', null , [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('handle_color', null, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('is_sphere', ChoiceType::class, [
                'choices' => [
                    'No' => false,
                    'Yes' => true
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
                'constraints' => [
                    new NotNull()
                ]
            ])
            ->add('sizeSquare')
            ->add('sizeRectangleA')
            ->add('sizeRectangleB')
            ->add('sizeCircle')
            ->add('sizeEllipseA')
            ->add('sizeEllipseB')
            ->add('diameterIceBall')
            ->add('customSizeNote')
            ->add('size', null, [
                'constraints' => [
                    new NotBlank()
                ]
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