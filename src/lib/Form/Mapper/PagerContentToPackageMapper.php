<?php

namespace Edgar\EzUIInfoBundles\Form\Mapper;

use Edgar\EzUIInfoBundles\Repository\EdgarEzPackageRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Translation\Translator;

class PagerContentToPackageMapper
{
    /** @var Translator  */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function map(Pagerfanta $pager): array
    {
        $data = [];

        foreach ($pager as $content) {
            $status = $content->getStatus();
            switch ($status) {
                case EdgarEzPackageRepository::STATUS_ABNADONED:
                    $status = $this->translator->trans('Abandoned', [], 'edgarezuiinfobundles');
                    break;
                case EdgarEzPackageRepository::STATUS_DELETED:
                    $status = $this->translator->trans('Deleted', [], 'edgarezuiinfobundles');
                    break;
                default:
                    $status = '';
                    break;
            }

            $data[] = [
                'vendor' => $content->getVendor(),
                'name' => $content->getName(),
                'repository' => $content->getRepository(),
                'description' => $content->getDescription(),
                'last_modified' => $content->getLastModified(),
                'status' => $status,
            ];
        }

        return $data;
    }
}
