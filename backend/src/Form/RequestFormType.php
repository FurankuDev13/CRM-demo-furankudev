<?php

namespace App\Form;

use App\Entity\Request;
use App\Form\RequestDetailType;
use App\Repository\ContactRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $companyId = $options['companyId'];

        $builder
            ->add('requestType', null, [
                'label'    => "Type de demande",
                'placeholder' => 'Choisir un type',
                'multiple'=>false,
                'expanded' => false
            ])
            ->add('handlingStatus', null, [
                'label'    => "Statut de la demande",
                'placeholder' => 'Choisir un statut',
                'multiple'=>false,
                'expanded' => false
            ])
            ->add('contact', null, [
                'label'    => "Contact émetteur de la demande",
                'placeholder' => 'Choisir un contact',
                'multiple'=>false,
                'expanded' => false,
                'query_builder' => function (ContactRepository $contactRepo) use ($companyId) {
                    return $contactRepo->createQueryBuilder('co')
                    ->join('co.company', 'c')
                    ->addSelect('c')
                    ->andWhere('c.id LIKE :companyId')
                    ->setParameter('companyId', $companyId);
                },
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide'
                    ])
                ]
            ])
            ->add('title', TextType::class, [
                'label'    => "Intitulé de la demande",
                'attr' => [
                    'placeholder' => "titre",
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
            ->add('body', TextareaType::class, [
                'label'    => "Corps de la demande",
                'attr' => [
                    'placeholder' => "texte",
                    ],
            ])
            ->add('requestDetails', CollectionType::class, [
                'entry_type' => RequestDetailType::class,
                'entry_options' => ['label' => false],
                'label'    => "Le(s) produit(s) concerné(s): ",
                'allow_add' => true,
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Request::class,
            'companyId' => null,
        ]);
    }
}
