<?php

namespace Infrastructure\Form\ParcMachine;

use Domain\Machine\Data\Model\Machine;
use Domain\ParcMachine\Data\Contract\CreateParcMachineRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;

class ParcMachineFormType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $user = $this->security->getUser(); 
        
        $builder 
            ->add('machine', EntityType::class, [
                'label' => 'Machine',
                'class' => Machine::class,
                'choice_label' => function ($machine) {
                    return $machine->nom . ' (' . $machine->numeroIdentification . ')';
                },
                'choice_value'=>'id',
                'placeholder' => 'Sélectionner un machine',
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' =>'Ajout dans mon parc',
                'attr' => [
                    'class' => 'btn btn-primary',
            ],
                
            ]);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => CreateParcMachineRequest::class,
        ]);
    }
}