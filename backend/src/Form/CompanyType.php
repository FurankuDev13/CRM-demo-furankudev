<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Company;
use App\Entity\Discount;
use App\Form\CompanyAddressFormType;
use App\Repository\UserRepository;
use App\Repository\DiscountRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'    => "Nom de la société",
                'attr' => [
                    'placeholder' => "nom",
                    ],
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
                'label'    => "Description de la société",
                'attr' => [
                    'placeholder' => "description",
                    ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide'
                    ]),
                    new Length([
                        'min'        => 1,
                        'max'        => 300,
                        'minMessage' => 'Pas assez de caractères , attendu : {{ limit }}',
                        'maxMessage' => 'Trop de caractères, attendu: {{ limit }}',
                    ])
                ]
            ])
            ->add('picture', UrlType::class, [
                'label'    => "Url de l'image",
                'attr' => ['placeholder' => "url"],
            ])
            ->add('sirenNumber', TextType::class, [
                'label'    => "Siren de la société",
                'attr' => [
                    'placeholder' => "siren",
                    ],
                'constraints' => [
                    new Length([
                        'min'        => 1,
                        'max'        => 100,
                        'minMessage' => 'Pas assez de caractères , attendu : {{ limit }}',
                        'maxMessage' => 'Trop de caractères, attendu: {{ limit }}',
                    ])
                ]
            ])
            ->add('isCustomer', CheckboxType::class, [
                'label'    => "Attribuer à la société le statut de client ?",
            ])
            ->add('discount', EntityType::class, [
                'class' => Discount::class,
                'label'    => "Remise accordée à la société: ",
                'multiple'=>false,
                'expanded' => false,
                'query_builder' => function (DiscountRepository $discountRepo) {
                    return $discountRepo->createQueryBuilder('d')
                    ->orderBy('d.rate', 'ASC');
                }
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label'    => "Le commercial chargé du suivi de la société: ",
                'multiple'=>false,
                'expanded' => false,
                'query_builder' => function (UserRepository $userRepo) {
                    return $userRepo->createQueryBuilder('u')
                    ->join('u.userRoles', 'r')
                    ->addSelect('r')
                    ->join('u.person', 'p')
                    ->addSelect('r')
                    ->andWhere('r.code = :val')
                    ->setParameter('val', 'ROLE_SALES')
                    ->orderBy('p.lastname', 'ASC');
                }
            ])
            ->add('companyAddresses', CollectionType::class, [
                'entry_type' => CompanyAddressFormType::class,
                'entry_options' => ['label' => false],
                'label'    => "Le(s) adresse(s): ",
                'allow_add' => true,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
