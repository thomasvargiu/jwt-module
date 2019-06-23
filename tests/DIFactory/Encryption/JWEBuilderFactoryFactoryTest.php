<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Encryption;

use Jose\Component\Core\AlgorithmManagerFactory;
use Jose\Component\Encryption\Compression\CompressionMethodManagerFactory;
use Jose\Component\Encryption\JWEBuilderFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use TMV\JWTModule\DIFactory\Encryption\JWEBuilderFactoryFactory;

class JWEBuilderFactoryFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $container = $this->prophesize(ContainerInterface::class);
        $algorithmManagerFactory = $this->prophesize(AlgorithmManagerFactory::class);
        $compressionManagerFactory = $this->prophesize(CompressionMethodManagerFactory::class);

        $container->get(AlgorithmManagerFactory::class)->willReturn($algorithmManagerFactory->reveal());
        $container->get(CompressionMethodManagerFactory::class)->willReturn($compressionManagerFactory->reveal());

        $factory = new JWEBuilderFactoryFactory();

        $service = $factory($container->reveal());
        $this->assertInstanceOf(JWEBuilderFactory::class, $service);
    }
}
