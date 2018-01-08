<?php

namespace Edgar\EzUIInfoBundles\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfoBundlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'vendor',
                VendorChoiceType::class,
                [
                    'label' => 'Vendors',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'placeholder' => 'Choose a vendor',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
            ]);
    }
}
