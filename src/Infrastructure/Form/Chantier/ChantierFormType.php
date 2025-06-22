<?php

namespace Infrastructure\Form\Chantier;

use Domain\Machine\Data\Model\Machine;
use Domain\User\Data\Model\User;
use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;
use Domain\ParcMachine\Data\Model\ParcMachine;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ChantierFormType extends AbstractType
{
    public function __construct(
        private readonly ParcMachineRepositoryInterface $repository
    ){}
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isEdit = $options['is_edit'] ?? false;
        $user = $options['user'];	

        $parcMachines = $this->repository->findAllByUser($user);

        $builder
            ->add('machineSerialNumber', EntityType::class, [
                'class' => ParcMachine::class,
                'choices' => $parcMachines,
                'choice_label' => function (ParcMachine $parcMachine) {
                    $machine = $parcMachine->getMachine();
                    return $machine->numeroIdentification;
                },
                'choice_value' => 'id',
                'label' => 'chantiers.form.machine_serial.label',
                'attr' => [
                    'class' => 'form-control select2',
                    'data-placeholder' => 'Sélectionnez la machine',
                ],
                'label_attr' => ['class' => 'form-label'],
                'constraints' => [
                    new Assert\NotNull(['message' => 'Une machine doit être sélectionnée']),
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'chantiers.form.name.label',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom du chantier ne peut pas être vide']),
                    new Assert\Length([
                        'min' => 3,
                        'minMessage' => 'Le nom du chantier doit contenir au moins {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('chantierDate', DateType::class, [
                'label' => 'chantiers.form.chantier_date.label',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date du chantier est obligatoire']),
                ]
            ])
            ->add('surface', NumberType::class, [
                'label' => 'chantiers.form.surface.label',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'step' => 0.01,
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La surface est obligatoire']),
                    new Assert\Positive(['message' => 'La surface doit être positive']),
                ]
            ])
            ->add('duration', NumberType::class, [
                'label' => 'chantiers.form.duration.label',
                'scale' => 1,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'step' => 0.1,
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La durée est obligatoire']),
                    new Assert\Positive(['message' => 'La durée doit être positive']),
                ]
            ])
            ->add('surfaceTypes', ChoiceType::class, [
                'label' => 'chantiers.form.surface_types.label',
                'choices' => [
                    'TOIT' => 'TOIT',
                    'MUR' => 'MUR',
                    'SOL' => 'SOL',
                    'AUTRES' => 'AUTRES',
                ],
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\Count([
                        'min' => 1,
                        'minMessage' => 'Au moins un type de surface doit être sélectionné',
                    ]),
                ]
            ])
            ->add('materials', ChoiceType::class, [
                'label' => 'chantiers.form.materials.label',
                'choices' => [
                    'TOIT' => [
                        'TUILE CIMENT' => 'TUILE CIMENT',
                        'TUILE TERRE CUITE' => 'TUILE TERRE CUITE',
                        'PIERRE NATURELLE' => 'PIERRE NATURELLE',
                        'AUTRES' => 'AUTRES',
                    ],
                    'MUR' => [
                        'ENDUIT' => 'ENDUIT',
                        'PIERRE NATURELLE' => 'PIERRE NATURELLE',
                        'BARDAGE BOIS' => 'BARDAGE BOIS',
                        'BARDAGE METAL' => 'BARDAGE METAL',
                        'AUTRES' => 'AUTRES',
                    ],
                    'SOL' => [
                        'BOIS' => 'BOIS',
                        'PIERRE NATURELLE' => 'PIERRE NATURELLE',
                        'AUTRES' => 'AUTRES',
                    ],
                    'AUTRES' => [
                        'AUTRES' => 'AUTRES',
                    ],
                ],
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'class' => 'form-control select2',
                    'data-placeholder' => 'Sélectionnez les matériaux',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\Count([
                        'min' => 1,
                        'minMessage' => 'Au moins un matériau doit être sélectionné',
                    ]),
                ]
            ])
            ->add('encrassementLevel', ChoiceType::class, [
                'label' => 'chantiers.form.encrassement.label',
                'choices' => [
                    '1 - Peu sale' => 1,
                    '2 - Moyennement sale' => 2,
                    '3 - Très sale' => 3,
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le niveau d\'encrassement est obligatoire']),
                    new Assert\Range([
                        'min' => 1,
                        'max' => 3,
                        'notInRangeMessage' => 'Le niveau d\'encrassement doit être entre {{ min }} et {{ max }}',
                    ]),
                ]
            ])
            ->add('vetusteLevel', ChoiceType::class, [
                'label' => 'chantiers.form.vetuste.label',
                'choices' => [
                    '1 - Récent' => 1,
                    '2 - État d\'usage' => 2,
                    '3 - Très ancien' => 3,
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'état de vétusté est obligatoire']),
                    new Assert\Range([
                        'min' => 1,
                        'max' => 3,
                        'notInRangeMessage' => 'L\'état de vétusté doit être entre {{ min }} et {{ max }}',
                    ]),
                ]
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'chantiers.form.commentaire.label',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => $isEdit ? 'chantiers.form.submit.update' : 'chantiers.form.submit.create',
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
            'user'=> null
        ]);

        $resolver->setAllowedTypes('is_edit', 'bool');
        $resolver->setAllowedTypes('user', User::class);
    }
}