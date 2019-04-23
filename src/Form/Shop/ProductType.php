<?php

namespace App\Form\Shop;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('stock', IntegerType::class, [

            ])
            ->add('category', EntityType::class,[
                'class' => Category::class,
                'placeholder' => 'Please choose',
            ])
            ->add('price');
        if($options['is_new']) {
            $builder
                ->add('image2', FileType::class,[
                    'data_class' => null,
                    'label' => 'New Image File',
                    'constraints' => [
                        new NotBlank()
                    ]
                ]);
        }else{
            $builder
                ->add('image2', FileType::class,[
                    'data_class' => null,
                    'label' => 'New Image File'
                ]);
        }
        $builder
            ->add('intro')
            ->add('description', null, [
                'attr' => [
                    'rows' => 20,
                ]
            ])
            ->add('isActive', ChoiceType::class,[
                'choices' => [
                    'No' => false,
                    'Yes' => true
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
        $resolver->setRequired('is_new');
    }
}
