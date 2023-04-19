<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;


use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword',PasswordType::class,[
                'attr' =>[
                    'class'=>'form-control',
                    'minlength' => '2',
                    'maxlength'=> '50'
                ],
                'label'=>'Ancien mot de passe',
                'label_attr'=>[
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min'=>2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])

            ->add('newPassword',RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options'=>[
                    'attr' =>[
                        'class'=>'form-control',
                    ],

                    'label' => 'Nouveau mot de Passe',
                    'label_attr'=>[
                        'class' => 'form-label mt-4'
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe',
                    'label_attr'=>[
                        'class' => 'form-label mt-4'
                    ],
                    'attr' =>[
                        'class'=>'form-control',
                    ],
                ]
            ])



            ->add('submit',SubmitType::class,[
                'attr' =>[
                    'class' => 'btn btn-primary mt-4'
                ]
            ]);
    }


}
