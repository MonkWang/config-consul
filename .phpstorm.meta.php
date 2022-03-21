<?php

declare(strict_types=1);
/**
 * This file is part of config-consul.
 *
 * @link     https://github.com/friendofhyperf/config-consul
 * @document https://github.com/friendofhyperf/config-consul/blob/main/README.md
 * @contact  huangdijia@gmail.com
 * @license  https://github.com/friendofhyperf/config-consul/blob/main/LICENSE
 */
namespace PHPSTORM_META;

    // Reflect
    override(\Psr\Container\ContainerInterface::get(0), map('@'));
    override(\Hyperf\Utils\Context::get(0), map('@'));
    override(\Hyperf\Context\Context::get(0), map('@'));
    override(\make(0), map('@'));
