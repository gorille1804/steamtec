<?php

namespace Infrastructure\Form\Machine;

use Domain\User\Data\Model\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddUserToMachineFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $availableUsers = $options['available_users'] ?? [];
        
        $builder
            ->add('user', ChoiceType::class, [
                'label' => 'machines.form.add_user.user.label',
                'choices' => $availableUsers,
                'choice_label' => function (User $user) {
                    return $user->lastname . ' ' . $user->firstname . ' (' . $user->email . ')';
                },
                'choice_value' => 'id',
                'placeholder' => 'machines.form.add_user.user.placeholder',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('add', SubmitType::class, [
                'label' => 'machines.form.add_user.submit',
                'attr' => [
                    'class' => 'btn btn-success btn-sm',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'available_users' => [],
        ]);
    }
} 