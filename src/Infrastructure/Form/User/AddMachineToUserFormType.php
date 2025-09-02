<?php

namespace Infrastructure\Form\User;

use Domain\Machine\Data\Model\Machine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddMachineToUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $availableMachines = $options['available_machines'] ?? [];
        
        $builder
            ->add('machine', ChoiceType::class, [
                'label' => 'users.form.add_machine.machine.label',
                'choices' => $availableMachines,
                'choice_label' => function (Machine $machine) {
                    return $machine->nom . ' (' . $machine->numeroIdentification . ')';
                },
                'choice_value' => 'id',
                'placeholder' => 'users.form.add_machine.machine.placeholder',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('add', SubmitType::class, [
                'label' => 'users.form.add_machine.submit',
                'attr' => [
                    'class' => 'btn btn-success btn-sm',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'available_machines' => [],
        ]);
    }
}
