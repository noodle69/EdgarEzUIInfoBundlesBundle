<?php

namespace Edgar\EzUIInfoBundlesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EdgarEzPackage
 *
 * @ORM\Entity(repositoryClass="Edgar\EzUIInfoBundles\Repository\EdgarEzPackageRepository")
 * @ORM\Table(name="edgar_ez_package")
 */
class EdgarEzPackage
{
    /**
     * @var string
     *
     * @ORM\Column(name="vendor", type="string", length=255, nullable=false)
     * @ORM\Id
     */
    private $vendor;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @ORM\Id
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_modified", type="datetime", nullable=false)
     */
    private $lastModified;

    /**
     * @var string
     *
     * @ORM\Column(name="repository", type="string", length=255, nullable=false)
     */
    private $repository;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

    /**
     * @return string
     */
    public function getVendor(): string
    {
        return $this->vendor;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return \DateTime
     */
    public function getLastModified(): \DateTime
    {
        return $this->lastModified;
    }

    /**
     * @return string
     */
    public function getRepository(): string
    {
        return $this->repository;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param string $vendor
     * @return EdgarEzPackage
     */
    public function setVendor(string $vendor): self
    {
        $this->vendor = $vendor;
        return $this;
    }

    /**
     * @param string $name
     * @return EdgarEzPackage
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param \DateTime $lastModified
     * @return EdgarEzPackage
     */
    public function setLastModified(\DateTime $lastModified): self
    {
        $this->lastModified = $lastModified;
        return $this;
    }

    /**
     * @param string $repository
     * @return EdgarEzPackage
     */
    public function setRepository(string $repository): self
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * @param null|string $description
     * @return EdgarEzPackage
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param int $status
     * @return EdgarEzPackage
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }
}
