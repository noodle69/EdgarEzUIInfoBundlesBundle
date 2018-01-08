<?php

namespace Edgar\EzUIInfoBundles\Form\Factory;

use Edgar\EzUIInfoBundles\Form\Data\PackageData;
use Edgar\EzUIInfoBundles\Form\Type\InfoBundlesType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FormFactory
{
    /** @var FormFactoryInterface $formFactory */
    protected $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function listPackages(
        PackageData $data,
        ?string $name = null
    ): ?FormInterface {
        $name = $name ?: 'infobundles';

        return $this->formFactory->createNamed(
            $name,
            InfoBundlesType::class,
            $data,
            [
                'method' => Request::METHOD_GET,
                'csrf_protection' => false,
            ]
        );
    }
}
