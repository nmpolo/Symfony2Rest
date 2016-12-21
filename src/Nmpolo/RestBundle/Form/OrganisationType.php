<?php

namespace Nmpolo\RestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrganisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Nmpolo\RestBundle\Entity\Organisation',
                'csrf_protection' => false,
            )
        );
    }

    public function getName()
    {
        return 'organisation';
    }
}
