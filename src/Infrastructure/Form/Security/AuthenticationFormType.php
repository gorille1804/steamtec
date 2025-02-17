<?php

namespace Infrastructure\Form\Security;

use Domain\User\Data\Contract\AuthenticationRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AuthenticationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label'      => 'User Email',
                'attr'       => [
                    'class'       => 'form-control',
                    'placeholder' => 'username@email.com',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
            ])
            ->add('password', PasswordType::class, [
                'label'      => 'Password',
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
