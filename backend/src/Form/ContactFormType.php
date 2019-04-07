<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Contact;
use App\Entity\ContactType;
use App\Repository\CompanyRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\ContactTypeRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label'    => "Email de l'utilisateur",
                'attr' => [
                    'placeholder' => "email",
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
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'label'    => "Le société du contact: ",
                'placeholder' => 'Choisir une société',
                'multiple'=>false,
                'expanded' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide'
                    ]),
                ],
                'query_builder' => function (CompanyRepository $companyRepo) {
                    return $companyRepo->createQueryBuilder('c')
                    ->andWhere('c.isActive = :val')
                    ->setParameter('val', true)
                    ->orderBy('c.name', 'ASC');
                }
            ])
            ->add('contactType', EntityType::class, [
                'class' => ContactType::class,
                'label'    => "Le type de contact: ",
                'placeholder' => 'Choisir un type',
                'multiple'=>false,
                'expanded' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide'
                    ]),
                ],
                'query_builder' => function (ContactTypeRepository $contactTypeRepo) {
                    return $contactTypeRepo->createQueryBuilder('ct')
                    ->andWhere('ct.isActive = :val')
                    ->setParameter('val', true)
                    ->orderBy('ct.title', 'ASC');
                }
            ])
            ;  
        
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
