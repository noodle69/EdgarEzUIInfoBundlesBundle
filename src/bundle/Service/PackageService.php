<?php

namespace Edgar\EzUIInfoBundlesBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\QueryBuilder;
use Edgar\EzUIInfoBundles\Form\Data\PackageData;
use Edgar\EzUIInfoBundles\Form\Data\VendorData;
use Edgar\EzUIInfoBundles\Repository\EdgarEzPackageRepository;
use Edgar\EzUIInfoBundlesBundle\Entity\EdgarEzPackage;

class PackageService
{
    /** @var EdgarEzPackageRepository  */
    private $package;

    public function __construct(
        Registry $doctrineRegistry
    ) {
        $entityManager = $doctrineRegistry->getManager();
        $this->package = $entityManager->getRepository(EdgarEzPackage::class);
    }

    public function loadVendors(): array
    {
        $vendors = $this->package->loadVendors();
        foreach ($vendors as $key => $vendor) {
            $vendorData = new VendorData();
            $vendorData->setIdentifier($vendor['vendor']);
            $vendorData->setName($vendor['vendor']);

            $vendors[$key] = $vendorData;
        }

        return $vendors;
    }

    public function buildQuery(PackageData $data): QueryBuilder
    {
        return $this->package->buildQuery($data);
    }
}
