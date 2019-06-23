<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Encryption;

use Jose\Component\Checker\HeaderCheckerManagerFactory;
use Jose\Component\Encryption\JWEDecrypterFactory;
use Jose\Component\Encryption\JWELoaderFactory;
use Jose\Component\Encryption\Serializer\JWESerializerManagerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use TMV\JWTModule\DIFactory\Encryption\JWELoaderFactoryFactory;

class JWELoaderFactoryFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $container = $this->prophesize(ContainerInterface::class);
        $jweSerializerManagerFactory = $this->prophesize(JWESerializerManagerFactory::class);
        $jweDecrypterFactory = $this->prophesize(JWEDecrypterFactory::class);
        $headerCheckerManagerFactory = $this->prophesize(HeaderCheckerManagerFactory::class);

        $container->get(JWESerializerManagerFactory::class)->willReturn($jweSerializerManagerFactory->reveal());
        $container->get(JWEDecrypterFactory::class)->willReturn($jweDecrypterFactory->reveal());
        $container->get(HeaderCheckerManagerFactory::class)->willReturn($headerCheckerManagerFactory->reveal());

        $factory = new JWELoaderFactoryFactory();

        $service = $factory($container->reveal());
        $this->assertInstanceOf(JWELoaderFactory::class, $service);
    }
}
