<?php

namespace App\Form;

use App\Entity\Contact;
use PharIo\Manifest\Email;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName',TextType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'minlength'=>'2',
                    'maxlength'=>'50'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Length(['min'=>2, 'max' => 50]),
                ]
            ])
            ->add('email',EmailType::class,[
                'attr' =>[
                    'class'=>'form-control',
                    'minlength' => '2',
                    'maxlength'=> '180'
                ],
                'label'=>'Email',
                'label_attr'=>[
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Email(),
                    new Assert\Length(['min'=>2, 'max' => 50]),
                ]

            ])

            ->add('subject',TextType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'minlength'=>'2',
                    'maxlength'=>'50'
                ],
                'label' => 'Subject',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Length(['min'=>2, 'max' => 50]),
                ]
            ])

            ->add('message', TextareaType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'minlength'=>'2',
                    'maxlength'=>'50'
                ],
                'label' => 'Message',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Length(['min'=>2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])

            ->add('captcha', Recaptcha3Type::class, [
                        'constraints' => new Recaptcha3(['message' => 'There were problems with your captcha. Please try again or contact with support and provide following code(s): {{ errorCodes }}']),
                        'action_name' => 'contact',

                    ])

            ->add('submit',SubmitType::class,[
                'attr' =>[
                    'class' => 'btn btn-primary mt-4'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
