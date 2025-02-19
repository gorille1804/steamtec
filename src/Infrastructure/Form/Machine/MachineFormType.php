<?php

namespace Infrastructure\Form\Machine;

use Domain\Machine\Data\Contract\CreateMachineRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Correct TextType import
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Domain\User\Data\Model\User;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class MachineFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isEdit = $options['is_edit'] ?? false;
        $builder
            ->add('numeroIdentification', TextType::class, [
                'label' => 'Numéro d\'identification',
                'attr' => ['placeholder' => 'Entrez le numéro d\'identification'],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom de la machine',
                'attr' => ['placeholder' => 'Entrez le nom de la machine'],
            ])
            ->add('marque', TextType::class, [
                'label' => 'Marque',
                'attr' => ['placeholder' => 'Entrez la marque de la machine'],
            ])
            ->add('tempUsage', IntegerType::class, [
                'label' => 'Temps d\'utilisation (heures)',
                'required' => false,
                'attr' => ['placeholder' => 'Durée d\'utilisation en heures'],
            ])
            ->add('seuilMaintenance', IntegerType::class, [
                'label' => 'Seuil de maintenance (heures)',
                'attr' => ['placeholder' => 'Seuil de maintenance en heures'],
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'firstname',
                'choice_value'=>'id',
                'placeholder' => 'Sélectionner un utilisateur (optionnel)',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => $isEdit ? 'Mettre à jour' : 'Créer',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'data-edit' => $isEdit ? 'true' : 'false'
            ],
                
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => CreateMachineRequest::class,
           'is_edit' => false,
        ]);
        $resolver->setAllowedTypes('is_edit', 'bool');
    }
}