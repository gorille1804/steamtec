<?php

namespace Infrastructure\Form\Security;

use Domain\User\Data\Contract\ResetPasswordRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Regex;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class, [
                'label' => 'users.reset_passwords.form.password.label',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'users.reset_passwords.form.password.placeholder',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'users.reset_passwords.form.password.empty',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'users.reset_passwords.form.password.min',
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
                        'message' => 'users.reset_passwords.form.password.validation',
                    ]),
                ],
            ])
            ->add('password_confirmation', PasswordType::class, [
                'label' => 'users.reset_passwords.form.password_confirmation.label',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'users.reset_passwords.form.password_confirmation.placeholder',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'users.reset_passwords.form.password_confirmation.empty',
                    ]),
                    new EqualTo([
                        'propertyPath' => 'parent.all[password].data',
                        'message' => 'users.reset_passwords.form.password_confirmation.not_equals',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ResetPasswordRequest::class,
        ]);
    }
}