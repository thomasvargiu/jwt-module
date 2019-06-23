<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Encryption\Compression;

use Jose\Component\Encryption\Compression\CompressionMethod;
use Jose\Component\Encryption\Compression\CompressionMethodManagerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use TMV\JWTModule\DIFactory\Encryption\Compression\CompressionMethodManagerFactoryFactory;

class CompressionMethodManagerFactoryFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $item1 = $this->prophesize(CompressionMethod::class);
        $item2 = $this->prophesize(CompressionMethod::class);
        $item3 = $this->prophesize(CompressionMethod::class);

        $item3->name()->willReturn('item3');

        $config = [
            'jwt_module' => [
                'compression_method_manager' => [
                    'compression_methods' => [
                        'foo' => $item1,
                        'bar' => 'item2',
                        'item',
                    ],
                ],
            ],
        ];

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);

        $container->get('item2')->shouldBeCalled()->willReturn($item2->reveal());
        $container->get('item')->shouldBeCalled()->willReturn($item3->reveal());

        $factory = new CompressionMethodManagerFactoryFactory();
        $service = $factory($container->reveal());

        $this->assertCount(3, $service->all());
        $this->assertSame($item1->reveal(), $service->all()['foo'] ?? null);
        $this->assertSame($item2->reveal(), $service->all()['bar'] ?? null);
        $this->assertSame($item3->reveal(), $service->all()['item3'] ?? null);

        $this->assertInstanceOf(CompressionMethodManagerFactory::class, $service);
    }
}
