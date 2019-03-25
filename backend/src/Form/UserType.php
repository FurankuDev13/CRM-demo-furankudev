<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserRole;
use App\Entity\Person;
use App\Form\PersonType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use App\Repository\UserRoleRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $listener = function (FormEvent $event) {
            $form = $event->getForm();
            $user = $event->getData();
            if (is_null($user->getId())) {
                $form->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Le mot de passe indiqué doit être identique dans les deux champs',
                    'options' => ['attr' => ['class' => 'password-field', 'placeholder' => "mot de passe"]],
                    'required' => true,
                    'first_options'  => ['label' => 'Mot de passe','empty_data' => ''],
                    'second_options' => ['label' => 'Répéter le mot de passe','empty_data' => '',],
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
                ]);
            } else {
                $form ->add('password', PasswordType::class, [
                    'empty_data' => '',
                    'label'    => "Mot de passe de l'utilisateur",
                    'attr' => ['placeholder' => 'Laisser vide si inchangé'],
                ]);
            }
        };

        $builder
            /* ->add('person') */
            ->add('email', EmailType::class, [
                'label'    => "Email de l'utilisateur",
                'attr' => [
                    'placeholder' => "email",
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
            ->addEventListener(FormEvents::PRE_SET_DATA, $listener);  
            
            if ($options['user']) {
                $user = $options['user'];
                $adminRole = false;
                foreach($user->getUserRoles() as $role) {
                    if ($role->getCode() == "ROLE_ADMIN") { 
                        $adminRole = true;
                    }
                }
                if ($adminRole) {
                    $builder->add('userRoles', EntityType::class, [
                        'class' => UserRole::class,
                        'label'    => "Les rôles accordés à l'utilisateur: ",
                        'multiple'=>true,
                        'expanded' => true,
                        'query_builder' => function (UserRoleRepository $userRoleRepo) {
                            return $userRoleRepo->createQueryBuilder('ur')
                            ->orderBy('ur.title', 'ASC');
                        }
                    ]);
                } else {
                    $builder->add('userRoles', EntityType::class, [
                        'class' => UserRole::class,
                        'label'    => "Les rôles accordés à l'utilisateur: ",
                        'multiple'=>true,
                        'expanded' => true,
                        'query_builder' => function (UserRoleRepository $userRoleRepo) {
                            return $userRoleRepo->createQueryBuilder('ur')
                            ->andWhere('ur.code != :val')
                            ->setParameter('val', 'ROLE_ADMIN')
                            ->orderBy('ur.title', 'ASC');
                        }
                    ]);
                }
            }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'user' => User::class,
        ]);
    }
}
