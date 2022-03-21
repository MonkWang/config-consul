<?php

declare(strict_types=1);

namespace Czan\ConfigConsul;

use Czan\ConfigConsul\Consul\KVInterface;
use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class Client implements ClientInterface
{
    private \FriendsOfHyperf\ConfigConsul\Consul\KVInterface $client;

    private \Hyperf\Contract\ConfigInterface $config;

    public function __construct(ContainerInterface $container)
    {
        $this->client = $container->get(KVInterface::class);
        $this->config = $container->get(ConfigInterface::class);
    }

    public function pull(): array
    {
        $namespaces = $this->config->get('config_center.drivers.consul.namespaces', '');
        $kvs = [];

        foreach ($namespaces as $namespace) {
            $res = $this->client->get($namespace, ['recurse' => true])->json();

            foreach ($res as $kv) {
                $kvs[$kv['Key']] = $kv;
            }
        }

        return $kvs;
    }
}
