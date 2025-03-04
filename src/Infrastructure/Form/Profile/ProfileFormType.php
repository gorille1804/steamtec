<?php
namespace Infrastructure\Form\Profile; 

use Domain\User\Data\Contract\UpdateUserRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'profils.form.firstname.label',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'profils.form.firstname.empty']),
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'profils.form.lastname.label',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'profils.form.lastname.empty']),
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'profils.form.phone.label',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'profils.form.phone.empty']),
                    new Assert\Regex([
                        'pattern' => '/^[0-9\-\+\s\(\)]+$/',
                        'message' => 'profils.form.phone.validation'
                    ])
                ]
            ])
            ->add('socity', TextType::class, [
                'label' => 'profils.form.socity.label',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'profils.form.socity.empty']),
                ]
            ])
            ->add('update', SubmitType::class, [
                'label' => 'profils.form.submit.update',
                'attr'  => [
                    'class' => 'btn btn-primary w-100 mb-3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UpdateUserRequest::class
        ]);
    }
}