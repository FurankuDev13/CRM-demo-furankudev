<?php

namespace App\Form;

use App\Entity\Comment;
use App\Form\AttachmentType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Intitulé du commentaire' ,
                'attr' => ['placeholder' => 'titre'],
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('body', TextareaType::class, [
                'label' => 'Commentaire' ,
                'attr' => ['placeholder' => 'texte'],
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('isOnBoard', CheckboxType::class, [
                'label'    => "Afficher le commentaire en alerte sur le tableau de bord ?",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
