<?php

namespace Edgar\EzUIInfoBundlesBundle\Cron;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Edgar\Cron\Cron\AbstractCron;
use Edgar\EzUIInfoBundles\API\PackagistAPI;
use Edgar\EzUIInfoBundles\Repository\EdgarEzPackageRepository;
use Edgar\EzUIInfoBundlesBundle\Entity\EdgarEzPackage;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BundlesCron extends AbstractCron
{
    /** @var EdgarEzPackageRepository */
    private $packageRepository;

    /** @var PackagistAPI */
    private $packagistAPI;

    public function __construct(
        ?string $name = null,
        EntityManager $entityManager,
        PackagistAPI $packagistAPI
    ) {
        parent::__construct($name);
        $this->packageRepository = $entityManager->getRepository(EdgarEzPackage::class);
        $this->packagistAPI = $packagistAPI;
    }

    protected function configure()
    {
        $this
            ->setName('edgarez:bundles:update')
            ->addArgument('type', InputArgument::REQUIRED, 'packages type')
            ->setDescription('Update community bundles list');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start Bundle registration');
        $type = $this->getArgument($input, 'type');
        if ($type) {
            try {
                $this->packageRepository->recordPackages($type, $this->packagistAPI, $output);
            } catch (ORMException $e) {
                $output->writeln('Fail to register packages: ' . $e->getMessage());
            }

            $output->writeln('Bundle registration ended');
        }
    }
}
