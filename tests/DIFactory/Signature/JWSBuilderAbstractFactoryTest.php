<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Signature;

use Interop\Container\ContainerInterface;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\JWSBuilderFactory;
use PHPUnit\Framework\TestCase;
use TMV\JWTModule\DIFactory\Signature\JWSBuilderAbstractFactory;

class JWSBuilderAbstractFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $requestedName = 'jwt_module.jws_builder.builder1';

        $config = [
            'jwt_module' => [
                'jws_builder' => [
                    'builder1' => [
                        'algorithms' => ['alg1'],
                    ],
                ],
            ],
        ];

        $builder = $this->prophesize(JWSBuilder::class);

        $builderFactory = $this->prophesize(JWSBuilderFactory::class);
        $builderFactory->create(['alg1'])
            ->willReturn($builder->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);
        $container->get(JWSBuilderFactory::class)
            ->willReturn($builderFactory->reveal());

        $factory = new JWSBuilderAbstractFactory();

        $this->assertTrue($factory->canCreate($container->reveal(), $requestedName));

        $service = $factory($container->reveal(), $requestedName);

        $this->assertSame($builder->reveal(), $service);
    }
}
