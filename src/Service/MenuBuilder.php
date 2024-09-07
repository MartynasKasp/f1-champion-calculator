<?php

namespace App\Service;

use App\Annotation\MenuItem;
use Symfony\Component\Routing\RouterInterface;

class MenuBuilder
{
    public function __construct(
        private RouterInterface $router
    ) {
    }

    public function buildMenuItems(): array
    {
        $menuItems = [];
        $routes = $this->router->getRouteCollection()->all();

        foreach ($routes as $route) {
            $defaults = $route->getDefaults();
            if (
                isset($defaults['_controller'])
                && str_starts_with($defaults['_controller'], 'App\\Controller\\Admin')
            ) {
                list($controllerService, $controllerMethod) = explode('::', $defaults['_controller']);
                $reflection = new \ReflectionMethod($controllerService, $controllerMethod);
                /** @var MenuItem */
                $menuItemAttribute = $this->getReflectionAttributeInstance($reflection, MenuItem::class);

                if (null !== $menuItemAttribute) {
                    $menuItemAttribute->route = $route->getPath();
                    $menuItems[] = $menuItemAttribute;
                }
            }
        }

        usort($menuItems, fn (MenuItem $left, MenuItem $right) => $left->priority > $right->priority);
        return $menuItems;
    }

    /**
     * @return null|object
     */
    private function getReflectionAttributeInstance(\ReflectionMethod $reflection, string $class)
    {
        $attributes = $reflection->getAttributes($class);
        if (count($attributes)) {
            return current($attributes)->newInstance();
        }
        return null;
    }
}
