<?php

namespace PixellHub\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterEntityType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'attr' => array(
                    'placeholder' => 'Nome'
                )
            ))
            ->add('surname', 'text', array(
                'attr' => array(
                    'placeholder' => 'Cognome'
                )
            ))
            ->add('email', 'text', array(
                'attr' => array(
                    'placeholder' => 'Email'
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PixellHub\NewsletterBundle\Entity\NewsletterEntity'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pixellhub_newsletterbundle_newsletterentity';
    }
}
