<?php

namespace Edgar\EzUIInfoBundles\SystemInfo\Collector;

use Doctrine\ORM\EntityManager;
use Edgar\EzUIInfoBundles\Repository\EdgarEzPackageRepository;
use Edgar\EzUIInfoBundles\SystemInfo\Value\BundlesSystemInfo;
use Edgar\EzUIInfoBundlesBundle\Entity\EdgarEzPackage;
use EzSystems\EzSupportToolsBundle\SystemInfo\Collector\SystemInfoCollector;

class BundlesSystemInfoCollector implements SystemInfoCollector
{
    /** @var EdgarEzPackageRepository  */
    private $packageRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->packageRepository = $entityManager->getRepository(EdgarEzPackage::class);
    }

    public function collect()
    {
        $bundles = $this->packageRepository->findAll();

        return new BundlesSystemInfo([
            'bundles' => $bundles,
        ]);
    }
}
