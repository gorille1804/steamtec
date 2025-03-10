<?php

namespace Infrastructure\Form\Machine;

use Domain\Machine\Data\Contract\CreateMachineRequest;
use Infrastructure\Symfony\Form\Type\FileInterfaceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Correct TextType import
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class MachineFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isEdit = $options['is_edit'] ?? false;
        $builder
            ->add('numeroIdentification', TextType::class, [
                'label' => 'machines.form.num_id.label',
                'attr' => ['placeholder' => 'machines.form.num_id.placeholder'],
            ])
            ->add('nom', TextType::class, [
                'label' => 'machines.form.name.label',
                'attr' => ['placeholder' => 'machines.form.name.placeholder'],
            ])
            ->add('marque', TextType::class, [
                'label' => 'machines.form.brand.label',
                'attr' => ['placeholder' => 'machines.form.brand.placeholder'],
            ])
            ->add('seuilMaintenance', IntegerType::class, [
                'label' => 'machines.form.seuil_maintenance.label',
                'attr' => ['placeholder' => 'machines.form.seuil_maintenance.placeholder'],
            ])
            ->add('ficheTechnique', FileInterfaceType::class, [
                'label' => 'machines.form.file.label',
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => $isEdit ? 'machines.form.submit.update' : 'machines.form.submit.create',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'data-edit' => $isEdit ? 'true' : 'false'
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