<?php

namespace Infrastructure\Form\MachineLog;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Domain\ParcMachine\Data\Model\ParcMachine;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints as Assert;

class MachineLogEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parcMachine', EntityType::class, [
                'class' => ParcMachine::class,
                'choices' => $options['parcMachines'],
                'choice_label' => function (ParcMachine $parcMachine) {
                    $machine = $parcMachine->getMachine();
                    return $machine->nom . ' (' . $machine->numeroIdentification . ')';
                },
                'label' => 'Machine',
                'required' => true,
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Durée (heures)',
                'required' => true,
                'constraints' => [
                    new Assert\GreaterThan([
                        'value' => 0,
                        'message' => 'La durée doit être positive'
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'parcMachines' => [],
        ]);
    }
}