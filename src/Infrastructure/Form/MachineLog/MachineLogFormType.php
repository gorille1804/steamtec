<?php

namespace Infrastructure\Form\MachineLog;

use Domain\MachineLog\Data\Contract\CreateMachineLogRequest;
use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
class MachineLogFormType extends AbstractType
{
    public function __construct(
        private readonly ParcMachineRepositoryInterface $repository
    ){}

    public function buildForm(FormBuilderInterface $builder, array $options)
{
    $parcMachines = $options['parcMachines'] ?? $this->repository->findAllByUser($options['user']);

    $builder
        ->add('logs', CollectionType::class, [
            'entry_type' => MachineLogEntryType::class,
            'entry_options' => [
                'parcMachines' => $parcMachines,
            ],
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'label' => false,
        ])
        ->add('logDate', DateType::class, [
            'label' => 'chantiers.shows.modals.forms.log_date.label',
            'widget' => 'single_text',
            'html5' => true,
            'attr' => [
                'class' => 'form-control',
            ],
            'label_attr' => [
                'class' => 'form-label',
            ],
            'constraints' => [
                new Assert\NotBlank(['message' => 'chantiers.shows.modals.forms.log_date.empty']),
                new Assert\LessThanOrEqual([
                    'value' => 'now',
                    'message' => 'chantiers.shows.modals.forms.log_date.date_futur_validation'
                ]),
            ],
            'data' => new \DateTime()
        ])
        ->add('save', SubmitType::class, [
            'label' => 'chantiers.shows.modals.forms.save',
            'attr' => [
                'class' => 'btn btn-primary w-100 mb-3',
            ],
        ]);
}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CreateMachineLogRequest::class,
            'parcMachines' => [],
        ]);
    }


}