<?php

namespace Infrastructure\Symfony\Form\Type;

use Domain\Shared\Data\ObjectValue\FileInterface;
use Infrastructure\Symfony\Services\File\SymfonyFileAdapter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

class FileInterfaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CallbackTransformer(
            // From domain to form
            function ($fileInterface) {
                // Return null because we can't set the file input's value
                // The file will be displayed in the template if available
                return null;
            },
            // From form to domain
            function ($uploadedFile) {
                if ($uploadedFile instanceof UploadedFile) {
                    return new SymfonyFileAdapter($uploadedFile);
                }
                
                return null;
            }
        ));
        
        // Add event listener to handle when no new file is uploaded
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            
            // If no new file is uploaded and we're in edit mode, keep the original file
            if (empty($data) && $form->getData() instanceof FileInterface) {
                $event->setData($form->getData());
            }
        });
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['file_label'] = $options['file_label'];
        $fileInterface = $form->getData();
        
        if ($fileInterface instanceof FileInterface) {
            $view->vars['file_name'] = $fileInterface->getFilename();
            $view->vars['has_file'] = true;
        } else {
            $view->vars['has_file'] = false;
        }
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'file_label' => 'File',
            'empty_data' => null,
            'attr' => [
                'accept' => '.jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.txt'
            ]
        ]);
        
        $resolver->setAllowedTypes('file_label', 'string');
    }
    
    public function getParent()
    {
        return FileType::class;
    }
}