<?php
namespace Infrastructure\Form\User;

use Domain\User\Data\Contract\CreateUserRequest;
use Domain\User\Data\Contract\UpdateUserRequest;
use Domain\User\Data\Enum\RoleEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isEdit = $options['is_edit'] ?? false;

        // Only add email field for creation
        if (!$isEdit) {
            $builder->add('email', EmailType::class, [
                'label' => 'users.form.email.label',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'users.form.email.empty']),
                    new Assert\Email(['message' => 'users.form.email.validation']),
                ]
            ]);
        }

        $builder
            ->add('firstname', TextType::class, [
                'label' => 'users.form.firstname.label',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'users.form.firstname.empty']),
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'users.form.lastname.label',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'users.form.lastname.empty']),
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'users.form.phone.label',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'users.form.phone.empty']),
                    new Assert\Regex([
                        'pattern' => '/^[0-9\-\+\s\(\)]+$/',
                        'message' => 'users.form.phone.validation'
                    ])
                ]
            ])
            ->add('socity', TextType::class, [
                'label' => 'users.form.socity.label',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'users.form.socity.empty']),
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'users.form.role.label',
                'choices' => [
                    'users.form.role.choice.user' => RoleEnum::USER->value,
                    'users.form.role.choice.admin' => RoleEnum::ADMIN->value,
                ],
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'form-check-input'];
                },
                'constraints' => [
                    new Assert\NotBlank(['message' => 'users.form.role.empty']),
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => $isEdit ? 'users.form.submit.update' : 'users.form.submit.create',
                'attr' => [
                    'class' => 'btn btn-primary w-100 mb-3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'is_edit' => false,
        ]);

        $resolver->setAllowedTypes('is_edit', 'bool');
    }
}