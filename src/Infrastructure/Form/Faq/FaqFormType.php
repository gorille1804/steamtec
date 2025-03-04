<?php

namespace Infrastructure\Form\Faq;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FaqFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', TextType::class, [
                'label' => 'FAQs.form.question.label',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'FAQs.form.question.empty',
                    ]),
                ],
            ])
            ->add('answer', TextareaType::class, [
                'label' => 'FAQs.form.answer.label',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'FAQs.form.answer.empty',
                    ]),
                ],
            ]);
        if ($options['is_edit']) {
            $builder->add('isActive', CheckboxType::class, [
                'label' => 'FAQs.form.active.label',
                'required' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'is_edit' => false,
        ]);
        
        $resolver->setAllowedTypes('is_edit', 'bool');
    }
}