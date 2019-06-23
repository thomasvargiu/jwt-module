<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Checker;

use Interop\Container\ContainerInterface;
use Jose\Component\Checker\ClaimChecker;
use Jose\Component\Checker\ClaimCheckerManagerFactory;
use PHPUnit\Framework\TestCase;
use TMV\JWTModule\DIFactory\Checker\ClaimCheckerManagerFactoryFactory;

class ClaimCheckerManagerFactoryFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $checker1 = $this->prophesize(ClaimChecker::class);
        $checker2 = $this->prophesize(ClaimChecker::class);

        $config = [
            'jwt_module' => [
                'claim_checker_manager' => [
                    'checkers' => [
                        'foo' => $checker1,
                        'bar' => 'checker2',
                    ],
                ],
            ],
        ];

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);

        $container->get('checker2')->shouldBeCalled()->willReturn($checker2->reveal());

        $factory = new ClaimCheckerManagerFactoryFactory();
        $service = $factory($container->reveal());

        $this->assertCount(2, $service->all());
        $this->assertSame($checker1->reveal(), $service->all()['foo'] ?? null);
        $this->assertSame($checker2->reveal(), $service->all()['bar'] ?? null);

        $this->assertInstanceOf(ClaimCheckerManagerFactory::class, $service);
    }
}
