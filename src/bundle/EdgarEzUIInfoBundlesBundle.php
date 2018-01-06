<?php

namespace Edgar\EzUIInfoBundlesBundle;

use Edgar\EzUIInfoBundlesBundle\DependencyInjection\Security\PolicyProvider\UIInfoBundlesPolicyProvider;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\EzPublishCoreExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EdgarEzUIInfoBundlesBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        /** @var EzPublishCoreExtension $eZExtension */
        $eZExtension = $container->getExtension('ezpublish');
        $eZExtension->addPolicyProvider(new UIInfoBundlesPolicyProvider($this->getPath()));
    }
}
