<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Signature;

use Jose\Component\Checker\HeaderCheckerManagerFactory;
use Jose\Component\Signature\JWSLoaderFactory;
use Jose\Component\Signature\JWSVerifierFactory;
use Jose\Component\Signature\Serializer\JWSSerializerManagerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use TMV\JWTModule\DIFactory\Signature\JWSLoaderFactoryFactory;

class JWSLoaderFactoryFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get(JWSSerializerManagerFactory::class)
            ->willReturn($this->prophesize(JWSSerializerManagerFactory::class)->reveal());
        $container->get(JWSVerifierFactory::class)
            ->willReturn($this->prophesize(JWSVerifierFactory::class)->reveal());
        $container->get(HeaderCheckerManagerFactory::class)
            ->willReturn($this->prophesize(HeaderCheckerManagerFactory::class)->reveal());

        $factory = new JWSLoaderFactoryFactory();
        $service = $factory($container->reveal());

        $this->assertInstanceOf(JWSLoaderFactory::class, $service);
    }
}
