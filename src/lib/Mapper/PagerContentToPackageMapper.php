<?php

namespace Edgar\EzUIInfoBundles\Mapper;

use Pagerfanta\Pagerfanta;

class PagerContentToPackageMapper
{
    public function map(Pagerfanta $pager): array
    {
        $data = [];

        foreach ($pager as $content) {
            $data[] = [
                'vendor' => $content->getVendor(),
                'name' => $content->getName(),
                'repository' => $content->getRepository(),
                'description' => $content->getDescription(),
                'last_modified' => $content->getLastModified()
            ];
        }

        return $data;
    }
}
