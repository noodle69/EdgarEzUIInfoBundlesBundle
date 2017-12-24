<?php

namespace Edgar\EzUIInfoBundlesBundle\DependencyInjection;

use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

class EdgarEzUIInfoBundlesExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('services.yml');
        $this->prependViews($container);
    }

    private function prependViews(ContainerBuilder $container): void
    {
        $configFile = __DIR__ . '/../Resources/config/views.yml';
        $config = Yaml::parse(file_get_contents($configFile));
        $parameters = $container->getParameter('ezsettings.global.system_info_view');
        $parameters['pjax_tab'] = array_merge($parameters['pjax_tab'], $config['global']['system_info_view']['pjax_tab']);
        $container->setParameter('ezsettings.global.system_info_view', $parameters);
        $container->addResource(new FileResource($configFile));
    }
}
