<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\NestedToken;

use Interop\Container\ContainerInterface;
use Jose\Component\NestedToken\NestedTokenBuilder;
use Jose\Component\NestedToken\NestedTokenBuilderFactory;
use PHPUnit\Framework\TestCase;
use TMV\JWTModule\DIFactory\NestedToken\NestedTokenBuilderAbstractFactory;

class NestedTokenBuilderAbstractFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $requestedName = 'jwt_module.nested_token_builder.builder1';

        $config = [
            'jwt_module' => [
                'nested_token_builder' => [
                    'builder1' => [
                        'jwe_serializers' => ['serializer1'],
                        'key_encryption_algorithms' => ['alg1'],
                        'content_encryption_algorithms' => ['alg2'],
                        'compression_methods' => ['compression1'],
                        'jws_serializers' => ['serializer2'],
                        'signature_algorithms' => ['alg3'],
                    ],
                ],
            ],
        ];

        $builder = $this->prophesize(NestedTokenBuilder::class);

        $builderFactory = $this->prophesize(NestedTokenBuilderFactory::class);
        $builderFactory->create(
            ['serializer1'],
            ['alg1'],
            ['alg2'],
            ['compression1'],
            ['serializer2'],
            ['alg3']
        )
            ->willReturn($builder->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);
        $container->get(NestedTokenBuilderFactory::class)
            ->willReturn($builderFactory->reveal());

        $factory = new NestedTokenBuilderAbstractFactory();

        $this->assertTrue($factory->canCreate($container->reveal(), $requestedName));

        $service = $factory($container->reveal(), $requestedName);

        $this->assertSame($builder->reveal(), $service);
    }
}
