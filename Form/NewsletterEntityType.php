<?php

namespace PixellHub\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterEntityType extends AbstractType
{
    /**
     * @var boolean $is_super_admin
     */
    protected $is_super_admin = false;
    
    /**
     * @var array $CKEditorConfig
     */
    protected $CKEditorConfig= array(
            'toolbar' => array(
                array(
                    'name'  => 'basicstyles',
                    'items' => array('Bold', 'Italic', 'Underline', 'TextColor')
                ),
                array(
                    'name'  => 'editorstyles',
                    'items' => array('Image', 'Table', 'HorizontalRule', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'FontSize', 'BulletedList', 'NumberedList', 'Link', 'Source')
                )
            ),
            'uiColor' => '#ffffff'
        );

    public function __construct($is_super_admin = false)
    {
        if ($is_super_admin) {
            $this->is_super_admin = $is_super_admin;
        }
    }

        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'attr' => array(
                    'label' => '',
                    'placeholder' => 'Nome'
                )
            ))
            ->add('surname', 'text', array(
                'attr' => array(
                    'label' => '',
                    'placeholder' => 'Cognome'
                )
            ))
            ->add('email', 'text', array(
                'attr' => array(
                    'label' => '',
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
