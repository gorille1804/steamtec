<?php

namespace Infrastructure\Form\Entretien;

use Domain\Entretien\Data\Contract\CreateEntretienLogRequest;
use Domain\ParcMachine\UseCase\FindAllParcMachineByUserUseCaseInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EntretienLogFormType extends AbstractType
{
    public function __construct(
        private readonly FindAllParcMachineByUserUseCaseInterface $findAllParcMachineByUserUseCase
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $parcMachines = $this->findAllParcMachineByUserUseCase->__invoke($user);
        
        $parcMachineChoices = [];
        foreach ($parcMachines as $parcMachine) {
            $machine = $parcMachine->getMachine();
            $parcMachineChoices[$machine->getNom() . ' (' . $machine->getNumeroIdentification() . ')'] = $parcMachine;
        }

        $builder
            ->add('parcMachine', ChoiceType::class, [
                'label' => 'Machine',
                'choices' => $parcMachineChoices,
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner une machine']),
                ]
            ])
            ->add('logDate', DateType::class, [
                'label' => 'Date du log',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner une date']),
                ]
            ])
            ->add('volumeHoraire', IntegerType::class, [
                'label' => 'Volume horaire (heures)',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le volume horaire est requis']),
                    new Assert\Positive(['message' => 'Le volume horaire doit être positif']),
                ]
            ])
            ->add('activite', TextareaType::class, [
                'label' => 'Activité d\'entretien effectuée',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez décrire l\'activité d\'entretien']),
                    new Assert\Length([
                        'min' => 10,
                        'max' => 1000,
                        'minMessage' => 'L\'activité doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'L\'activité ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CreateEntretienLogRequest::class,
            'user' => null,
        ]);
    }
} 