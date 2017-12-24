<?php

namespace Edgar\EzUIInfoBundles\SystemInfo\Value;

use eZ\Publish\API\Repository\Values\ValueObject;
use EzSystems\EzSupportToolsBundle\SystemInfo\Value\SystemInfo;

class BundlesSystemInfo extends ValueObject implements SystemInfo
{
    /** @var array */
    public $bundles;
}
