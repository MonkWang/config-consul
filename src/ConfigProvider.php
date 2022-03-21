<?php

declare(strict_types=1);

namespace Czan\ConfigConsul;

class ConfigProvider
{
    public function __invoke(): array
    {
        defined('BASE_PATH') or define('BASE_PATH', __DIR__ . '/../../../');

        return [
            'dependencies' => [
                ClientInterface::class => Client::class,
                Consul\KVInterface::class => Consul\KVFactory::class,
            ],
        ];
    }
}
