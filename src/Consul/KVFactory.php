<?php

declare(strict_types=1);

namespace Czan\ConfigConsul\Consul;

use Hyperf\Consul\KV;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Guzzle\ClientFactory;
use Psr\Container\ContainerInterface;

class KVFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new KV(function () use ($container) {
            $config = $container->get(ConfigInterface::class);
            $token = $config->get('config_center.drivers.consul.token', '');
            $options = [
                'timeout' => 2,
                'base_uri' => $config->get('config_center.drivers.consul.uri', KV::DEFAULT_URI),
            ];

            if (! empty($token)) {
                $options['headers'] = [
                    'X-Consul-Token' => $token,
                ];
            }

            return $container->get(ClientFactory::class)->create($options);
        });
    }
}
