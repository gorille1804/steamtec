<?php

namespace Infrastructure\Form\Security;

use Domain\User\Data\Contract\AuthenticationRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class AuthenticationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label'       => 'User Email',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Email address is required']),
                    new Assert\Email(['message' => 'Please enter a valid email address']),
                ],
                'attr'       => [
                    'class'       => 'form-control',
                    'placeholder' => 'username@email.com',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
            ])
            ->add('password', PasswordType::class, [
                'label'       => 'Password',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Password is required']),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Password must be at least {{ limit }} characters long',
                    ]),
                ],
                'attr'       => [
                    'class'       => 'form-control',
                    'placeholder' => '••••••••',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
            ])
            ->add('login', SubmitType::class, [
                'label' => 'Login',
                'attr'  => [
                    'class' => 'btn btn-primary w-100 mb-3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AuthenticationRequest::class,
        ]);
    }
}