<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Encryption;

use Jose\Component\Encryption\Serializer\JWESerializer;
use Jose\Component\Encryption\Serializer\JWESerializerManagerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use TMV\JWTModule\DIFactory\Encryption\JWESerializerManagerFactoryFactory;

class JWESerializerManagerFactoryFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $item1 = $this->prophesize(JWESerializer::class);
        $item2 = $this->prophesize(JWESerializer::class);
        $item3 = $this->prophesize(JWESerializer::class);

        $item1->name()->willReturn('item1');
        $item2->name()->willReturn('item2');
        $item3->name()->willReturn('item3');

        $config = [
            'jwt_module' => [
                'jwe_serializer_manager' => [
                    'serializers' => [
                        $item1,
                        'item2',
                        'item',
                    ],
                ],
            ],
        ];

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);

        $container->get('item2')->shouldBeCalled()->willReturn($item2->reveal());
        $container->get('item')->shouldBeCalled()->willReturn($item3->reveal());

        $factory = new JWESerializerManagerFactoryFactory();
        $service = $factory($container->reveal());

        $this->assertCount(3, $service->all());
        $this->assertSame($item1->reveal(), $service->all()['item1'] ?? null);
        $this->assertSame($item2->reveal(), $service->all()['item2'] ?? null);
        $this->assertSame($item3->reveal(), $service->all()['item3'] ?? null);

        $this->assertInstanceOf(JWESerializerManagerFactory::class, $service);
    }
}
