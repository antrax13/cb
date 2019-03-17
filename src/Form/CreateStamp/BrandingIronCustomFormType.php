<?php

namespace App\Form\CreateStamp;

use App\Entity\BrandingIronSize;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class BrandingIronCustomFormType extends AbstractType
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
            ->add('qty', IntegerType::class, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan(0)
                ]
            ])
            ->add('sizeOptions', EntityType::class, [
                'class' => BrandingIronSize::class,
                'placeholder' => 'Please choose',
                'label' => 'Select preferred size',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('alkRackOption', ChoiceType::class, [
                'constraints' => [
                    new NotBlank()
                ],
                'label' => 'Do you need ALK Rack?',
                'choices' => [
                    'Yes' => 'Yes',
                    'No' => 'No'
                ]
            ])
            ->add('comment', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'rows' => 2
                ],
                'help' => 'if you have any additional comment please enter it here'
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
