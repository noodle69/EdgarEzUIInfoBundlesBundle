<?php

namespace Edgar\EzUIInfoBundles\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;
use Edgar\EzUIInfoBundles\API\PackagistAPI;
use Edgar\EzUIInfoBundlesBundle\Entity\EdgarEzPackage;
use Symfony\Component\Console\Output\OutputInterface;

class EdgarEzPackageRepository extends EntityRepository
{
    public function recordPackages(string $type, PackagistAPI $packagistAPI, OutputInterface $output)
    {
        $packages = $this->listPackages($type, $packagistAPI);

        $this->purgePackages($packages, $output);

        foreach ($packages as $vendor => $names) {
            foreach ($names as $name) {
                $package = $this->getPackageInfo($vendor, $name);
                $edgarEzPackage = $packagistAPI->getPackageInfo($vendor, $name);
                if (!$package || $package->getLastModified()->diff($edgarEzPackage->getLastModified())->s !== 0) {
                    if ($package) {
                        $package->setLastModified($edgarEzPackage->getLastModified());
                    } else {
                        $package = $edgarEzPackage;
                    }

                    $output->writeln('Register package : ' . $package->getVendor() . '/' . $package->getName());
                    $this->recordPackage($package, $output);
                }
            }
        }
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
}
