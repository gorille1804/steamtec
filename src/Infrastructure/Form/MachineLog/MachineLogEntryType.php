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
                'label' => 'chantiers.shows.modals.forms.parc_machine.label',
                'required' => true,
            ])
            ->add('duration', NumberType::class, [
                'label' => 'chantiers.shows.modals.forms.duration.label',
                'required' => true,
                'constraints' => [
                    new Assert\GreaterThan([
                        'value' => 0,
                        'message' => 'chantiers.shows.modals.forms.duration.duration_validation'
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