<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019-10-18
 * Time: 12:38
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', null, [
                'placeholder' => 'Choose user',
            ])
            ->add('product', null, [
                'placeholder' => 'Choose product',
            ])
            ->add('quantity', IntegerType::class, [
                'attr' => [
                    'min' => 1,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Order'
        ]);
    }


}