<?php

namespace App\Form;

use App\Entity\CompanyAddress;
use App\Entity\CompanyAddressType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CompanyAddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstAddressField', TextType::class, [
                'label' => 'Adresse' ,
                'attr' => ['placeholder' => '1er champ d\'adresse'],
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('secondAddressField', TextType::class, [
                'label' => 'Adresse (complément)' ,
                'attr' => ['placeholder' => '2nd champ d\'adresse (optionnel)'],
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code Postal' ,
                'attr' => ['placeholder' => 'code postal avec ou sans espace après les 2 premiers chiffres'],
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min'   => 5,
                        'max'   => 6,
                        'minMessage' => 'Entrer un code postal avec ou sans espace après les 2 premiers chiffres',
                        'maxMessage' => 'Entrer un code postal avec ou sans espace après les 2 premiers chiffres',
                    ])
               	]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville' ,
                'attr' => ['placeholder' => 'ville'],
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays' ,
                'attr' => ['placeholder' => 'pays'],
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('companyAddressType', EntityType::class, [
                'class' => CompanyAddressType::class,
                'label'    => "Type d'adresse: ",
                'multiple'=>false,
                'expanded' => true,
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CompanyAddress::class,
        ]);
    }
}
