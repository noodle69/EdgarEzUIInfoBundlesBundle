<?php

namespace Edgar\EzUIInfoBundles\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;

class PackageData
{
    /**
     * @var int
     *
     * @Assert\Range(
     *     max = 1000
     * )
     */
    private $limit;

    /** @var int */
    private $page;

    /** @var VendorData */
    private $vendor;

    public function __construct(
        int $limit = 10,
        int $page = 1,
        ?VendorData $vendor = null
    ) {
        $this->limit = $limit;
        $this->page = $page;
        $this->vendor = $vendor;
    }

    /**
     * @param int $limit
     *
     * @return PackageData
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param int $page
     *
     * @return PackageData
     */
    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function setVendor(?VendorData $vendor): self
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    public function getVendor(): ?VendorData
    {
        return $this->vendor;
    }
}
