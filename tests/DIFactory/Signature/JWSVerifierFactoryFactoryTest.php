<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Signature;

use Jose\Component\Core\AlgorithmManagerFactory;
use Jose\Component\Signature\JWSVerifierFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use TMV\JWTModule\DIFactory\Signature\JWSVerifierFactoryFactory;

class JWSVerifierFactoryFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get(AlgorithmManagerFactory::class)
            ->willReturn($this->prophesize(AlgorithmManagerFactory::class)->reveal());

        $factory = new JWSVerifierFactoryFactory();
        $service = $factory($container->reveal());

        $this->assertInstanceOf(JWSVerifierFactory::class, $service);
    }
}
