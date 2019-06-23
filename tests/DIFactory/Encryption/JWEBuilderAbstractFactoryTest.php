<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Encryption;

use Interop\Container\ContainerInterface;
use Jose\Component\Encryption\JWEBuilder;
use Jose\Component\Encryption\JWEBuilderFactory;
use PHPUnit\Framework\TestCase;
use TMV\JWTModule\DIFactory\Encryption\JWEBuilderAbstractFactory;

class JWEBuilderAbstractFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $requestedName = 'jwt_module.jwe_builder.builder1';

        $config = [
            'jwt_module' => [
                'jwe_builder' => [
                    'builder1' => [
                        'key_encryption_algorithms' => ['alg1'],
                        'content_encryption_algorithms' => ['alg2'],
                        'compression_methods' => ['compression1'],
                    ],
                ],
            ],
        ];

        $builder = $this->prophesize(JWEBuilder::class);

        $builderFactory = $this->prophesize(JWEBuilderFactory::class);
        $builderFactory->create(['alg1'], ['alg2'], ['compression1'])
            ->willReturn($builder->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);
        $container->get(JWEBuilderFactory::class)
            ->willReturn($builderFactory->reveal());

        $factory = new JWEBuilderAbstractFactory();

        $this->assertTrue($factory->canCreate($container->reveal(), $requestedName));

        $service = $factory($container->reveal(), $requestedName);

        $this->assertSame($builder->reveal(), $service);
    }
}
