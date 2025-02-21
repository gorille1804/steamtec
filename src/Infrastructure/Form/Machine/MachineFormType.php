<?php

namespace Infrastructure\Form\Machine;

use Domain\Machine\Data\Contract\CreateMachineRequest;
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
            ->add('seuilMaintenance', IntegerType::class, [
                'label' => 'Seuil de maintenance (heures)',
                'attr' => ['placeholder' => 'Seuil de maintenance en heures'],
            ])
            ->add('ficheTechnique', FileType::class, [
                'label' => 'Upload File',
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'application/pdf'],
                        'mimeTypesMessage' => 'Veuillez uploader un fichier valide (JPG, PNG, PDF)',
                    ])
                ],
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