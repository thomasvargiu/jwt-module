<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest;

use PHPUnit\Framework\TestCase;
use TMV\JWTModule\Module;

class ModuleTest extends TestCase
{
    public function testGetConfig(): void
    {
        $module = new Module();
        $config = $module->getConfig();

        $this->assertArrayHasKey('service_manager', $config);
        $this->assertArrayNotHasKey('dependencies', $config);
    }
}
