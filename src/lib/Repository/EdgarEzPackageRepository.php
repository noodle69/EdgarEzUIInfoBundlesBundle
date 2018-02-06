<?php

namespace Edgar\EzUIInfoBundles\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Edgar\EzUIInfoBundles\API\PackagistAPI;
use Edgar\EzUIInfoBundles\Form\Data\PackageData;
use Edgar\EzUIInfoBundlesBundle\Entity\EdgarEzPackage;
use Symfony\Component\Console\Output\OutputInterface;

class EdgarEzPackageRepository extends EntityRepository
{
    const STATUS_OK = 1;
    const STATUS_ABNADONED = 2;
    const STATUS_DELETED = 3;

    /**
     * @param string $type
     * @param PackagistAPI $packagistAPI
     * @param OutputInterface $output
     *
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function recordPackages(string $type, PackagistAPI $packagistAPI, OutputInterface $output)
    {
        $packages = $this->listPackages($type, $packagistAPI);

        $this->purgePackages($packages, $output);

        foreach ($packages as $vendor => $names) {
            foreach ($names as $name) {
                $package = $this->getPackageInfo($vendor, $name);
                $edgarEzPackage = $packagistAPI->getPackageInfo($vendor, $name);
                if (!$package
                    || $package->getLastModified()->diff($edgarEzPackage->getLastModified())->s !== 0
                    || $package->getStatus() != $edgarEzPackage->getStatus()
                ) {
                    if ($package) {
                        $package->setLastModified($edgarEzPackage->getLastModified());
                        $package->setStatus($edgarEzPackage->getStatus());
                    } else {
                        $package = $edgarEzPackage;
                    }

                    $output->writeln('Register package : ' . $package->getVendor() . '/' . $package->getName());
                    $this->recordPackage($package, $output);
                }
            }
        }
    }

    public function getPackages()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('p')
            ->from(EdgarEzPackage::class, 'p')
            ->orderBy('p.lastModified', 'DESC');

        return $queryBuilder;
    }

    protected function listPackages(string $type, PackagistAPI $packagistAPI): array
    {
        $packages = $packagistAPI->listByType($type);

        $return = [];
        foreach ($packages as $package) {
            list($vendor, $name) = explode('/', $package);
            $return[$vendor][] = $name;
        }

        return $return;
    }

    protected function getPackageInfo(string $vendor, string $name): ?EdgarEzPackage
    {
        return $this->findOneBy([
            'vendor' => $vendor,
            'name' => $name,
        ]);
    }

    protected function recordPackage(EdgarEzPackage $package, OutputInterface $output)
    {
        try {
            $this->getEntityManager()->persist($package);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            $output->writeln('Failed to register package : ' . $package->getVendor() . '/' . $package->getName());
        }
    }

    /**
     * @param array $packages
     * @param OutputInterface $output
     *
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function purgePackages(array $packages, OutputInterface $output)
    {
        $entityManager = $this->getEntityManager();

        /** @var EdgarEzPackage[] $edgarEzPackages */
        $edgarEzPackages = $this->findAll();
        foreach ($edgarEzPackages as $package) {
            if (!isset($packages[$package->getVendor()])
                || !in_array($package->getName(), $packages[$package->getVendor()])) {
                $output->writeln('Remove package : ' . $package->getVendor() . '/' . $package->getName());
                $entityManager->remove($package);
            }
        }

        $entityManager->flush();
    }

    public function buildQuery(PackageData $data): QueryBuilder
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('p')
            ->from(EdgarEzPackage::class, 'p')
            ->orderBy('p.lastModified', 'DESC');

        if ($data->getVendor()) {
            $queryBuilder
                ->where($queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('p.vendor', ':vendor')
                ))
                ->setParameter('vendor', $data->getVendor()->getIdentifier());
        }

        return $queryBuilder;
    }

    public function loadVendors(): array
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        $vendorsQuery = $queryBuilder->select('p.vendor')
            ->from(EdgarEzPackage::class, 'p')
            ->orderBy('p.vendor', 'ASC')
            ->distinct()
            ->getQuery();

        return $vendorsQuery->getArrayResult();
    }
}
