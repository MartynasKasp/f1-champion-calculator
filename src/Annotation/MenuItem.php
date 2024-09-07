<?php

namespace App\Annotation;

#[\Attribute(\Attribute::TARGET_METHOD)]
class MenuItem
{
    public string $route;

    public function __construct(
        public string $label = '',
        public string $icon = '',
        public bool $disabled = false,
        public int $priority = 0,
    ) {
    }
}
