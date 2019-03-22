<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference')
            ->add('name')
            ->add('description')
            ->add('picture')
            ->add('listPrice')
            ->add('maxDiscountRate')
            ->add('isOnHomePage')
            ->add('rank')
            ->add('isActive')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('isAvailable')
            ->add('categories')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
