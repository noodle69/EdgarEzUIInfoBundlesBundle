<?php

namespace Edgar\EzUIInfoBundlesBundle\Controller;

use Edgar\EzUIInfoBundles\Form\Data\PackageData;
use Edgar\EzUIInfoBundles\Form\Data\VendorData;
use Edgar\EzUIInfoBundles\Form\Factory\FormFactory;
use Edgar\EzUIInfoBundles\Form\Mapper\PagerContentToPackageMapper;
use Edgar\EzUIInfoBundles\Form\SubmitHandler;
use Edgar\EzUIInfoBundlesBundle\Service\PackageService;
use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

class InfoBundlesController extends Controller
{
    /** @var PackageService */
    protected $packageService;

    /** @var FormFactory  */
    protected $formFactory;

    /** @var SubmitHandler  */
    protected $submitHandler;

    /** @var NotificationHandlerInterface $notificationHandler */
    protected $notificationHandler;

    /** @var TranslatorInterface */
    private $translator;

    /** @var PermissionResolver  */
    private $permissionResolver;

    /** @var PagerContentToPackageMapper  */
    private $pagerContentToPackageMapper;

    public function __construct(
        PackageService $packageService,
        FormFactory $formFactory,
        SubmitHandler $submitHandler,
        PagerContentToPackageMapper $pagerContentToPackageMapper,
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator,
        PermissionResolver $permissionResolver
    ) {
        $this->packageService = $packageService;
        $this->formFactory = $formFactory;
        $this->submitHandler = $submitHandler;
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
        $this->permissionResolver = $permissionResolver;
        $this->pagerContentToPackageMapper = $pagerContentToPackageMapper;
    }

    public function listAction(Request $request): Response
    {
        $this->permissionAccess('uiinfo', 'bundles');

        $infoBundlesType = $this->formFactory->listPackages(
            new PackageData()
        );
        $infoBundlesType->handleRequest($request);
        $infoBundlesType->getData()->setPage($request->get('page', 1));

        $pagerfanta = $this->getResults($infoBundlesType->getData(), $request->get('limit', 10), $request->get('page', 1));

        return $this->render('@EdgarEzUIInfoBundles/packages/list.html.twig', [
            'form_package' => $infoBundlesType->createView(),
            'results' => $this->pagerContentToPackageMapper->map($pagerfanta),
            'pager' => $pagerfanta,
        ]);
    }

    protected function getResults(PackageData $data, int $limit = 10, int $page = 1): Pagerfanta
    {
        $query = $this->packageService->buildQuery($data);
        $pagerfanta = new Pagerfanta(
            new DoctrineORMAdapter($query, false)
        );

        $pagerfanta->setMaxPerPage($limit);
        $pagerfanta->setCurrentPage(min($page, $pagerfanta->getNbPages()));

        return $pagerfanta;
    }

    protected function permissionAccess(string $module, string $function): ?RedirectResponse
    {
        if (!$this->permissionResolver->hasAccess($module, $function)) {
            $this->notificationHandler->error(
                $this->translator->trans(
                    'edgar.ezuicron.permission.failed',
                    [],
                    'edgarezuicron'
                )
            );
            return new RedirectResponse($this->generateUrl('ezplatform.dashboard', []));
        }

        return null;
    }
}
