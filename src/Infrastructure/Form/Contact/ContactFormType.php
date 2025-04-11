<?php 

namespace Infrastructure\Form\Contact;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\{TextType, EmailType, TextareaType, ChoiceType, SubmitType};
use Symfony\Component\OptionsResolver\OptionsResolver;
use Domain\Contact\Data\Contract\ContactRequest as ContractContactRequest;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('email', EmailType::class)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Société' => 'société',
                    'Collectivité' => 'collectivité',
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => ['class' => 'content_radio']
            ])
            ->add('message', TextareaType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer votre message',
                'attr' => ['class' => 'btn btn-primary w-100 mb-3']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContractContactRequest::class,
        ]);
    }
}
