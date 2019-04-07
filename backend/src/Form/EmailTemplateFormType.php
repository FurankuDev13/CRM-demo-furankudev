<?php

namespace App\Form;

use App\Entity\EmailTemplate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EmailTemplateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label'    => "Intitulé du template",
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
            ->add('messageTitle', TextType::class, [
                'label'    => "Header du messager",
                'attr' => [
                    'placeholder' => "titre",
                    ],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide'
                    ]),
                    new Length([
                        'min'        => 1,
                        'max'        => 200,
                        'minMessage' => 'Pas assez de caractères , attendu : {{ limit }}',
                        'maxMessage' => 'Trop de caractères, attendu: {{ limit }}',
                    ])
                ]
            ])
            ->add('messageBody', TextareaType::class, [
                'label'    => "Body du message",
                'attr' => [
                    'placeholder' => "corps",
                    ],
                    'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide'
                    ]),
                    new Length([
                        'min'        => 1,
                        'max'        => 900,
                        'minMessage' => 'Pas assez de caractères , attendu : {{ limit }}',
                        'maxMessage' => 'Trop de caractères, attendu: {{ limit }}',
                    ])
                ]
            ])
            ->add('messageSignature', TextareaType::class, [
                'label'    => "Footer du message",
                'attr' => [
                    'placeholder' => "signature",
                    ],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide'
                    ]),
                    new Length([
                        'min'        => 1,
                        'max'        => 900,
                        'minMessage' => 'Pas assez de caractères , attendu : {{ limit }}',
                        'maxMessage' => 'Trop de caractères, attendu: {{ limit }}',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EmailTemplate::class,
        ]);
    }
}
