<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Signature;

use Interop\Container\ContainerInterface;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use Jose\Component\Signature\Serializer\JWSSerializerManagerFactory;
use PHPUnit\Framework\TestCase;
use TMV\JWTModule\DIFactory\Signature\JWSSerializerAbstractFactory;

class JWSSerializerAbstractFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $requestedName = 'jwt_module.jws_serializer.service1';

        $config = [
            'jwt_module' => [
                'jws_serializer' => [
                    'service1' => [
                        'serializers' => ['serializer1'],
                    ],
                ],
            ],
        ];

        $manager = $this->prophesize(JWSSerializerManager::class);

        $managerFactory = $this->prophesize(JWSSerializerManagerFactory::class);
        $managerFactory->create(['serializer1'])
            ->willReturn($manager->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);
        $container->get(JWSSerializerManagerFactory::class)
            ->willReturn($managerFactory->reveal());

        $factory = new JWSSerializerAbstractFactory();

        $this->assertTrue($factory->canCreate($container->reveal(), $requestedName));

        $service = $factory($container->reveal(), $requestedName);

        $this->assertSame($manager->reveal(), $service);
    }
}
