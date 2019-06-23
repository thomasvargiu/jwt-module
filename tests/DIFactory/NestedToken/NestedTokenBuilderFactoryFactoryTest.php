<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\NestedToken;

use Jose\Component\Encryption\JWEBuilderFactory;
use Jose\Component\Encryption\Serializer\JWESerializerManagerFactory;
use Jose\Component\NestedToken\NestedTokenBuilderFactory;
use Jose\Component\Signature\JWSBuilderFactory;
use Jose\Component\Signature\Serializer\JWSSerializerManagerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use TMV\JWTModule\DIFactory\NestedToken\NestedTokenBuilderFactoryFactory;

class NestedTokenBuilderFactoryFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $jweBuilderFactory = $this->prophesize(JWEBuilderFactory::class);
        $jweSerializerManagerFactory = $this->prophesize(JWESerializerManagerFactory::class);
        $jwsBuilderFactory = $this->prophesize(JWSBuilderFactory::class);
        $jwsSerializerManagerFactory = $this->prophesize(JWSSerializerManagerFactory::class);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(JWEBuilderFactory::class)->willReturn($jweBuilderFactory->reveal());
        $container->get(JWESerializerManagerFactory::class)->willReturn($jweSerializerManagerFactory->reveal());
        $container->get(JWSBuilderFactory::class)->willReturn($jwsBuilderFactory->reveal());
        $container->get(JWSSerializerManagerFactory::class)->willReturn($jwsSerializerManagerFactory->reveal());

        $factory = new NestedTokenBuilderFactoryFactory();
        $service = $factory($container->reveal());

        $this->assertInstanceOf(NestedTokenBuilderFactory::class, $service);
    }
}
