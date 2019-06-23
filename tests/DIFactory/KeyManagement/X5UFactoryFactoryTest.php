<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\KeyManagement;

use Jose\Component\KeyManagement\X5UFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use TMV\JWTModule\DIFactory\KeyManagement\X5UFactoryFactory;

class X5UFactoryFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $container = $this->prophesize(ContainerInterface::class);
        $client = $this->prophesize(ClientInterface::class);
        $requestFactory = $this->prophesize(RequestFactoryInterface::class);

        $container->get('config')->willReturn([]);
        $container->get(ClientInterface::class)->willReturn($client->reveal());
        $container->get(RequestFactoryInterface::class)->willReturn($requestFactory->reveal());

        $factory = new X5UFactoryFactory();
        $service = $factory($container->reveal());

        $this->assertInstanceOf(X5UFactory::class, $service);
    }

    public function testInvokeWithCustomClient(): void
    {
        $config = [
            'jwt_module' => [
                'jku_factory' => [
                    'http_client' => 'my_http_client',
                ],
            ],
        ];

        $container = $this->prophesize(ContainerInterface::class);
        $client = $this->prophesize(ClientInterface::class);
        $requestFactory = $this->prophesize(RequestFactoryInterface::class);

        $container->get('config')->willReturn($config);
        $container->get('my_http_client')->shouldBeCalled()->willReturn($client->reveal());
        $container->get(RequestFactoryInterface::class)->willReturn($requestFactory->reveal());

        $factory = new X5UFactoryFactory();
        $service = $factory($container->reveal());

        $this->assertInstanceOf(X5UFactory::class, $service);
    }
}
