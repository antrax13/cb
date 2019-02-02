<?php
/**
 * Created by PhpStorm.
 * User: peterkosak
 * Date: 12/01/2019
 * Time: 00:39
 */

namespace App\Form\CreateStamp;


use App\Entity\StampQuote;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SummaryCommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('additionalComment', TextareaType::class, [
            'required' => false,
            'help' => 'leave blank if there are no special requirements',
            'label' => false,
            'attr' => [
                'rows' => 4,
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StampQuote::class,
        ]);
    }
}