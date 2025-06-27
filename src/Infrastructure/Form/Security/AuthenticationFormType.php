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
                'label'       => 'users.login.form.email.label',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'users.login.form.email.empty']),
                    new Assert\Email(['message' => 'users.login.form.email.validation']),
                ],
                'attr'       => [
                    'class'       => 'form-control',
                    'placeholder' => 'users.login.form.email.placeholder',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
            ])
            ->add('password', PasswordType::class, [
                'label'       => 'users.login.form.password.label',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'users.login.form.password.empty']),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'users.login.form.password.min',
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
                'label' => 'users.login.form.submit.login',
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