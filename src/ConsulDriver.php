<?php

declare(strict_types=1);

namespace Czan\ConfigConsul;

use Czan\ConfigConsul\Packer\Base64Packer;
use Hyperf\ConfigCenter\AbstractDriver;
use Hyperf\Utils\Packer\JsonPacker;
use Psr\Container\ContainerInterface;

class ConsulDriver extends AbstractDriver
{
    protected string $driverName = 'consul';

    protected ContainerInterface $container;

    /**
     * @var Base64Packer
     */
    protected $packer;

    /**
     * @var array
     */
    protected $mapping;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->client = $container->get(ClientInterface::class);
        $this->mapping = $this->config->get('config_center.drivers.consul.mapping', []);
        $this->packer = $container->get($this->config->get('config_center.drivers.consul.packer', JsonPacker::class));
    }

    protected function updateConfig(array $config): void
    {
        $configurations = $this->format($config);

        foreach ($configurations as $kv) {
            
            $key = $this->mapping[$kv->key] ?? null;

            if (is_string($key)) {
                $oldConfig = $this->config->get($kv);
                $consulConfig = $this->packer->unpack((string) $kv->value);
                $newConfig = $this->multimerge($oldConfig, $consulConfig);
                $this->config->set($key, $newConfig);
                $this->logger->debug(sprintf('Config [%s] is updated', $key));
            }
        }
    }

    /**
     * 多维数组递归合并
     */
    protected function multimerge(array $oldArr, array $newArr)
    {
        $arrs = func_get_args();
        $merged = array();
        while ($arrs) {
            $array = array_shift($arrs);
            if (!$array) {
                continue;
            }
            foreach ($array as $key => $value) {
                if (is_string($key)) {
                    if (is_array($value) && array_key_exists($key, $merged) && is_array($merged[$key])) {
                        // $merged[$key] = call_user_func(__FUNCTION__, $merged[$key], $value);
                        $merged[$key] = $this->multimerge($merged[$key], $value);
                    } else {
                        $merged[$key] = $value;
                    }
                } else {
                    $merged[] = $value;
                }
            }
        }
        return $merged;
    }

    /**
     * Format kv configurations.
     * @return KV[]
     */
    protected function format(array $config): array
    {
        $result = [];

        foreach ($config as $value) {
            $result[] = new KV($value);
        }

        return $result;
    }
}
