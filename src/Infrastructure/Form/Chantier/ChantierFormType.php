<?php

namespace Infrastructure\Form\Chantier;

use Domain\Chantier\Data\Contract\CreateChantierRequest;
use Domain\Machine\Data\Model\Machine;
use Domain\User\Data\Model\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ChantierFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isEdit = $options['is_edit'] ?? false;

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du chantier',
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
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 10
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La description ne peut pas être vide']),
                ]
            ])
            ->add('hours', NumberType::class, [
                'label' => 'Heures estimées',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\PositiveOrZero(['message' => 'Le nombre d\'heures doit être positif ou nul']),
                ]
            ])
            ->add('machines', EntityType::class, [
                'class' => Machine::class,
                'choice_label' => function ($machine) {
                    return $machine->nom . ' (' . $machine->numeroIdentification . ')';
                },
                'multiple' => true,
                'expanded' => false,
                'label' => 'Machines',
                'attr' => [
                    'class' => 'form-control select2', 
                    'data-placeholder' => 'Sélectionnez les machines',
                    'style' => 'width: 100%' 
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\Count([
                        'min' => 1,
                        'minMessage' => 'Au moins une machine doit être sélectionnée',
                    ]),
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => $isEdit ? 'Mettre à jour' : 'Créer',
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