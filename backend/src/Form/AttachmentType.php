<?php

namespace App\Form;

use App\Entity\Attachment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AttachmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', TextType::class, [
            'label' => 'Intitulé de la pièce jointe' ,
            'attr' => ['placeholder' => 'titre'],
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
        ->add('path', FileType::class,[
            'label' => 'Parcourir et attacher le fichier (PDF)',
            'required' => true,
            'empty_data' => null
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Attachment::class,
        ]);
    }
}
