# Hyperf config-consul

[![Latest Test](https://github.com/friendsofhyperf/config-consul/workflows/tests/badge.svg)](https://github.com/friendsofhyperf/config-consul/actions)
[![Latest Stable Version](https://poser.pugx.org/friendsofhyperf/config-consul/version.png)](https://packagist.org/packages/friendsofhyperf/config-consul)
[![Total Downloads](https://poser.pugx.org/friendsofhyperf/config-consul/d/total.png)](https://packagist.org/packages/friendsofhyperf/config-consul)
[![GitHub license](https://img.shields.io/github/license/friendsofhyperf/config-consul)](https://github.com/friendsofhyperf/config-consul)

## Installation

~~~base
composer require czan/config-consul
~~~

## Configure

~~~php
// config/autoload/config_center.php

return [
    'drivers' => [
        'consul' => [
            'driver' => Czan\ConfigConsul\ConsulDriver::class,
            'packer' => Hyperf\Utils\Packer\JsonPacker::class,
            'uri' => env('CONSUL_URI'),
            'namespaces' => [
                '/application',
            ],
            'mapping' => [
                // consul key => config key
                '/application/test' => 'test',
            ],
            'interval' => 5,
        ],
    ],
];
~~~
