<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\NestedToken;

use Jose\Component\Encryption\JWELoaderFactory;
use Jose\Component\NestedToken\NestedTokenLoaderFactory;
use Jose\Component\Signature\JWSLoaderFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use TMV\JWTModule\DIFactory\NestedToken\NestedTokenLoaderFactoryFactory;

class NestedTokenLoaderFactoryFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get(JWELoaderFactory::class)->willReturn($this->prophesize(JWELoaderFactory::class)->reveal());
        $container->get(JWSLoaderFactory::class)->willReturn($this->prophesize(JWSLoaderFactory::class)->reveal());

        $factory = new NestedTokenLoaderFactoryFactory();
        $service = $factory($container->reveal());

        $this->assertInstanceOf(NestedTokenLoaderFactory::class, $service);
    }
}
