<?php

namespace App\Extension;

use App\Service\MenuBuilder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MenuItemsExtension extends AbstractExtension
{
    public function __construct(
        private MenuBuilder $menuBuilder
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getMenuItems', [$this, 'getAdminMenu']),
        ];
    }

    /**
     * @return \App\Annotation\MenuItem[]
     */
    public function getAdminMenu(): array
    {
        return $this->menuBuilder->buildMenuItems();
    }
}
