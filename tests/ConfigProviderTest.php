<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest;

use PHPUnit\Framework\TestCase;
use TMV\JWTModule\ConfigProvider;

class ConfigProviderTest extends TestCase
{
    public function testInvoke(): void
    {
        $configProvider = new ConfigProvider();

        $this->assertIsCallable($configProvider);

        $config = $configProvider();

        $this->assertArrayHasKey('dependencies', $config);
        $this->assertIsArray($config['dependencies']);
    }
}
