<?php

namespace Edgar\EzUIInfoBundles\SystemInfo\Collector;

use Doctrine\ORM\EntityManager;
use Edgar\EzUIInfoBundles\Mapper\PagerContentToPackageMapper;
use Edgar\EzUIInfoBundles\Repository\EdgarEzPackageRepository;
use Edgar\EzUIInfoBundles\SystemInfo\Value\BundlesSystemInfo;
use Edgar\EzUIInfoBundlesBundle\Entity\EdgarEzPackage;
use EzSystems\EzSupportToolsBundle\SystemInfo\Collector\SystemInfoCollector;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class BundlesSystemInfoCollector implements SystemInfoCollector
{
    /** @var EdgarEzPackageRepository  */
    private $packageRepository;

    /** @var PagerContentToPackageMapper  */
    private $pagerContentToPackageMapper;

    public function __construct(
        EntityManager $entityManager,
        PagerContentToPackageMapper $pagerContentToPackageMapper
    ) {
        $this->packageRepository = $entityManager->getRepository(EdgarEzPackage::class);
        $this->pagerContentToPackageMapper = $pagerContentToPackageMapper;
    }

    public function collect()
    {
        $query = $this->packageRepository->getPackages();
        $pagerfanta = new Pagerfanta(
            new DoctrineORMAdapter($query, false)
        );

        $pagerfanta->setMaxPerPage(10);
        $pagerfanta->setCurrentPage(min(1, $pagerfanta->getNbPages()));

        return new BundlesSystemInfo([
            'bundles' => $this->pagerContentToPackageMapper->map($pagerfanta),
            'pager' => $pagerfanta,
        ]);
    }
}
