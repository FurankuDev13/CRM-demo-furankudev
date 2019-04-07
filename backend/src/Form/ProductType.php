<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference', TextType::class, [
                'label'    => "Référence du produit",
                'attr' => [
                    'placeholder' => "réference",
                    ],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide'
                    ]),
                    new Length([
                        'min'        => 1,
                        'max'        => 100,
                        'minMessage' => 'Pas assez de caractères , attendu : {{ limit }}',
                        'maxMessage' => 'Trop de caractères, attendu: {{ limit }}',
                    ])
                ]
            ])
            ->add('name', TextType::class, [
                'label'    => "Nom du produit",
                'attr' => [
                    'placeholder' => "nom",
                    ],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide'
                    ]),
                    new Length([
                        'min'        => 1,
                        'max'        => 100,
                        'minMessage' => 'Pas assez de caractères , attendu : {{ limit }}',
                        'maxMessage' => 'Trop de caractères, attendu: {{ limit }}',
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label'    => "Description du produit",
                'attr' => [
                    'placeholder' => "description",
                    ],
            ])
            ->add('picture', UrlType::class, [
                'label'    => "Url de l'image",
                'attr' => ['placeholder' => "url"],
            ])
            ->add('listPrice', MoneyType::class, [
                'label'    => "Prix liste du produit",
                'attr' => ['placeholder' => "montant"],
                'currency' => 'EUR',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide'
                    ]),
                    new Length([
                        'min'        => 1,
                        'max'        => 100,
                        'minMessage' => 'Pas assez de caractères , attendu : {{ limit }}',
                        'maxMessage' => 'Trop de caractères, attendu: {{ limit }}',
                    ])
                ]
            ])
            ->add('maxDiscountRate', PercentType::class, [
                'label'    => "Taux maximum de remise autorisé",
                'attr' => ['placeholder' => "pourcentage"],
                'type' => 'integer'
            ])
            ->add('isAvailable', CheckboxType::class, [
                'label'    => "Indiquer le produit comme disponible et l'afficher dans le catalogue ?",
            ])
            ->add('rank', IntegerType::class, [
                'label'    => "Ordre d'affichage du produit",
                'attr' => ['placeholder' => "chiffre"],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide'
                    ]),
                    new Length([
                        'min'        => 1,
                        'max'        => 100,
                        'minMessage' => 'Pas assez de caractères , attendu : {{ limit }}',
                        'maxMessage' => 'Trop de caractères, attendu: {{ limit }}',
                    ])
                ]
            ])
            ->add('isOnHomePage', CheckboxType::class, [
                'label'    => "Affichage sur la page d'acceuil en vitrine ?",
            ])
            ->add('categories', null, [
                'label'    => "Catégories",
                'multiple'=>true,
                'expanded' => true
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
