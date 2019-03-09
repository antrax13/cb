<?php

namespace App\Form\CreateStamp;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class HeatStampCustomFormType extends AbstractType
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
            ->add('customSizeNote', TextareaType::class, [
                'constraints' => [
                    new NotBlank()
                ],
                'attr' => [
                    'rows' => 2,
                ],
                'help' => 'please provide sizes of your heat stamp, ie if your logo is circular shape say diameter 40mm or for square 40mm, and for rectangular shapes 40x20mm. '
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
