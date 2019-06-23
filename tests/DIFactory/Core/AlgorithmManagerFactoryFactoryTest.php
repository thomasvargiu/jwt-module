<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Core;

use Interop\Container\ContainerInterface;
use Jose\Component\Core\Algorithm;
use Jose\Component\Core\AlgorithmManagerFactory;
use PHPUnit\Framework\TestCase;
use TMV\JWTModule\DIFactory\Core\AlgorithmManagerFactoryFactory;

class AlgorithmManagerFactoryFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $algorithm1 = $this->prophesize(Algorithm::class);
        $algorithm2 = $this->prophesize(Algorithm::class);
        $algorithm3 = $this->prophesize(Algorithm::class);

        $algorithm3->name()->willReturn('algorithm3');

        $config = [
            'jwt_module' => [
                'algorithm_manager' => [
                    'algorithms' => [
                        'foo' => $algorithm1,
                        'bar' => 'algorithm2',
                        'algorithm',
                    ],
                ],
            ],
        ];

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);

        $container->get('algorithm2')->shouldBeCalled()->willReturn($algorithm2->reveal());
        $container->get('algorithm')->shouldBeCalled()->willReturn($algorithm3->reveal());

        $factory = new AlgorithmManagerFactoryFactory();
        $service = $factory($container->reveal());

        $this->assertCount(3, $service->all());
        $this->assertSame($algorithm1->reveal(), $service->all()['foo'] ?? null);
        $this->assertSame($algorithm2->reveal(), $service->all()['bar'] ?? null);
        $this->assertSame($algorithm3->reveal(), $service->all()['algorithm3'] ?? null);

        $this->assertInstanceOf(AlgorithmManagerFactory::class, $service);
    }
}
