<?php

namespace Edgar\EzUIInfoBundlesBundle\EventListener;

use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use EzSystems\EzPlatformAdminUi\Menu\MainMenuBuilder;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use JMS\TranslationBundle\Model\Message;

class ConfigureMenuListener implements TranslationContainerInterface
{
    const ITEM_INFOBUNDLES = 'main__infobundles';

    /** @var PermissionResolver */
    private $permissionResolver;

    public function __construct(PermissionResolver $permissionResolver)
    {
        $this->permissionResolver = $permissionResolver;
    }

    /**
     * @param ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        if (!$this->permissionAccess('uiinfo', 'bundles')) {
            return;
        }

        $menu = $event->getMenu();

        $infoBundlesMenu = $menu->getChild(MainMenuBuilder::ITEM_ADMIN);
        $infoBundlesMenu->addChild(self::ITEM_INFOBUNDLES, ['route' => 'edgar.ezuiinfobundles.list']);
    }

    /**
     * @return array
     */
    public static function getTranslationMessages(): array
    {
        return [
            (new Message(self::ITEM_INFOBUNDLES, 'messages'))->setDesc('Community bundles'),
        ];
    }

    protected function permissionAccess(string $module, string $function): bool
    {
        if (!$this->permissionResolver->hasAccess($module, $function)) {
            return false;
        }

        return true;
    }
}
