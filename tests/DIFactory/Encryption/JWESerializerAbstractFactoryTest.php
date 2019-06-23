<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Encryption;

use Interop\Container\ContainerInterface;
use Jose\Component\Encryption\Serializer\JWESerializerManager;
use Jose\Component\Encryption\Serializer\JWESerializerManagerFactory;
use PHPUnit\Framework\TestCase;
use TMV\JWTModule\DIFactory\Encryption\JWESerializerAbstractFactory;

class JWESerializerAbstractFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $requestedName = 'jwt_module.jwe_serializer.service1';

        $config = [
            'jwt_module' => [
                'jwe_serializer' => [
                    'service1' => [
                        'serializers' => ['serializer1'],
                    ],
                ],
            ],
        ];

        $manager = $this->prophesize(JWESerializerManager::class);

        $managerFactory = $this->prophesize(JWESerializerManagerFactory::class);
        $managerFactory->create(['serializer1'])
            ->willReturn($manager->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);
        $container->get(JWESerializerManagerFactory::class)
            ->willReturn($managerFactory->reveal());

        $factory = new JWESerializerAbstractFactory();

        $this->assertTrue($factory->canCreate($container->reveal(), $requestedName));

        $service = $factory($container->reveal(), $requestedName);

        $this->assertSame($manager->reveal(), $service);
    }
}
