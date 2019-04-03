<?php

namespace App\Form;

use App\Entity\RequestDetail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RequestDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'label'    => "Produit",
                'placeholder' => 'Choisir un produit',
                'multiple'=>false,
                'expanded' => false
            ])
            /* ->add('discount', EntityType::class, [
                'class' => Discount::class,
                'label'    => "Remise accordée à la société: ",
                'placeholder' => 'Choisir une remise',
                'multiple'=>false,
                'expanded' => false,
                'query_builder' => function (DiscountRepository $discountRepo) {
                    return $discountRepo->createQueryBuilder('d')
                    ->orderBy('d.rate', 'ASC');
                }
            ]) */
            ->add('quantity', IntegerType::class, [
                'label'    => "Quantité souhaitée",
                'attr' => ['placeholder' => "chiffre"],
            ])
            ->add('commentField', TextareaType::class, [
                'label'    => "Commentaire sur ce produit",
                'attr' => [
                    'placeholder' => "texte",
                    ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RequestDetail::class,
        ]);
    }
}
