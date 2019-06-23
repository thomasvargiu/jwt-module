<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Signature;

use Jose\Component\Signature\Serializer\JWSSerializer;
use Jose\Component\Signature\Serializer\JWSSerializerManagerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use TMV\JWTModule\DIFactory\Signature\JWSSerializerManagerFactoryFactory;

class JWSSerializerManagerFactoryFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $item1 = $this->prophesize(JWSSerializer::class);
        $item2 = $this->prophesize(JWSSerializer::class);
        $item3 = $this->prophesize(JWSSerializer::class);

        $item1->name()->willReturn('item1');
        $item2->name()->willReturn('item2');
        $item3->name()->willReturn('item3');

        $item3->name()->willReturn('item3');

        $config = [
            'jwt_module' => [
                'jws_serializer_manager' => [
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

        $factory = new JWSSerializerManagerFactoryFactory();
        $service = $factory($container->reveal());

        $this->assertCount(3, $service->all());
        $this->assertSame($item1->reveal(), $service->all()['item1'] ?? null);
        $this->assertSame($item2->reveal(), $service->all()['item2'] ?? null);
        $this->assertSame($item3->reveal(), $service->all()['item3'] ?? null);

        $this->assertInstanceOf(JWSSerializerManagerFactory::class, $service);
    }
}
