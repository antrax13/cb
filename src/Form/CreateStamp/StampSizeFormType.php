<?php
/**
 * Created by PhpStorm.
 * User: peterkosak
 * Date: 10/01/2019
 * Time: 17:09
 */

namespace App\Form\CreateStamp;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StampSizeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sizeSideA',null,[

            ])
            ->add('sizeSideB',null,[

            ])
            ->add('sizeDiamter',null,[

            ])
            ->add('sizeCustomNote',null,[

            ])
            ->add('sizeNote',null,[

            ])
            ->add('sizeSphereDiameter',null,[

            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}