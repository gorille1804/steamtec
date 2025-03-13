<?php

namespace Infrastructure\Form\Security;

use Domain\User\Data\Contract\ForgotPasswordRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
class ForgotPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label'      => 'users.forgot_password.form.email.label',
                'attr'       => [
                    'class'       => 'form-control',
                    'placeholder' => 'users.forgot_password.form.email.placeholder',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
            ]);
    }   

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ForgotPasswordRequest::class,
        ]);
    }
}