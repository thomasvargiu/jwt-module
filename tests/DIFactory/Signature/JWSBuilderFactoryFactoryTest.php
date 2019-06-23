<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Signature;

use Jose\Component\Core\AlgorithmManagerFactory;
use Jose\Component\Signature\JWSBuilderFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use TMV\JWTModule\DIFactory\Signature\JWSBuilderFactoryFactory;

class JWSBuilderFactoryFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get(AlgorithmManagerFactory::class)
            ->willReturn($this->prophesize(AlgorithmManagerFactory::class)->reveal());

        $factory = new JWSBuilderFactoryFactory();
        $service = $factory($container->reveal());

        $this->assertInstanceOf(JWSBuilderFactory::class, $service);
    }
}
