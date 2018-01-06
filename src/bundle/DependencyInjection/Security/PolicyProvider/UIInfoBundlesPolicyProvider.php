<?php

namespace Edgar\EzUIInfoBundlesBundle\DependencyInjection\Security\PolicyProvider;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Security\PolicyProvider\YamlPolicyProvider;

class UIInfoBundlesPolicyProvider extends YamlPolicyProvider
{
    /** @var string $path bundle path */
    protected $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getFiles()
    {
        return [$this->path . '/Resources/config/policies.yml'];
    }
}
