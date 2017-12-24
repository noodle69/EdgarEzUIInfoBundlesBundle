<?php

namespace Edgar\EzUIInfoBundles\API;

use Edgar\EzUIInfoBundlesBundle\Entity\EdgarEzPackage;
use GuzzleHttp\Client;

class PackagistAPI
{
    /** @var string  */
    private $listPackageUrl;

    /** @var string  */
    private $packageInfoUrl;

    public function __construct(
        string $listPackageUrl,
        string $packageInfoUrl
    ) {
        $this->listPackageUrl = $listPackageUrl;
        $this->packageInfoUrl = $packageInfoUrl;
    }

    public function listByType(string $type): array
    {
        $client = new Client();
        $response = $client->request('GET', str_replace('<type>', $type, $this->listPackageUrl), [
            'headers' => [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ],
        ]);
        $body = $response->getBody();
        $packageList = json_decode($body->getContents(), true);

        return isset($packageList['packageNames']) ? $packageList['packageNames'] : [];
    }

    public function getPackageInfo(string $vendor, string $name): ?EdgarEzPackage
    {
        $client = new Client();
        $response = $client->request('GET', str_replace(['<vendor>', '<name>'], [$vendor, $name], $this->packageInfoUrl), [
            'headers' => [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ],
        ]);
        $body = $response->getBody();
        $packageInfo = json_decode($body->getContents(), true);
        if (!$packageInfo) {
            return null;
        }

        $packageInfo = $packageInfo['packages'][$vendor . '/' . $name]['dev-master'];

        $result = new EdgarEzPackage();
        $result->setVendor($vendor);
        $result->setName($name);
        $result->setRepository($packageInfo['source']['url']);
        $result->setDescription($packageInfo['description']);
        $result->setLastModified(new \DateTime($packageInfo['time']));
        $result->setStatus(1);

        return $result;
    }
}
