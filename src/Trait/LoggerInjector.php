<?php

namespace App\Trait;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait LoggerInjector
{
    protected ?LoggerInterface $logger = null;

    #[Required]
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
