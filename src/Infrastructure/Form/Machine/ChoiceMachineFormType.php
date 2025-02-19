<?php

namespace Infrastructure\Form\Machine;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Domain\Machine\Data\Model\Machine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Domain\Machine\Data\Contract\ChoiceMachineRequest;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;

class ChoiceMachineFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('machine', EntityType::class, [
            'class' => Machine::class,
            'choice_label' => 'nom',
            'choice_value' => 'id', 
            'placeholder' => 'Sélectionner une machine',
            'required' => true,
            'expanded' => false,
            'query_builder' => function (EntityRepository $er) {
                // Filtrer pour ne récupérer que les machines sans utilisateur
                return $er->createQueryBuilder('m')
                    ->leftJoin('m.user', 'u')
                    ->where('u.id IS NULL');
            }
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Ajouter dans dans le parc' ,
            'attr' => [
                'class' => 'btn btn-primary'
            ],  
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChoiceMachineRequest::class, 
        ]);
    }
}
