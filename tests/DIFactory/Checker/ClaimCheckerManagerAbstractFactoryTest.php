<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Checker;

use Interop\Container\ContainerInterface;
use Jose\Component\Checker\ClaimCheckerManager;
use Jose\Component\Checker\ClaimCheckerManagerFactory;
use PHPUnit\Framework\TestCase;
use TMV\JWTModule\DIFactory\Checker\ClaimCheckerManagerAbstractFactory;

class ClaimCheckerManagerAbstractFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $requestedName = 'jwt_module.claim_checker.checker1';

        $config = [
            'jwt_module' => [
                'claim_checker' => [
                    'checker1' => [
                        'claims' => [
                            'foo',
                        ],
                    ],
                ],
            ],
        ];

        $manager = $this->prophesize(ClaimCheckerManager::class);

        $managerFactory = $this->prophesize(ClaimCheckerManagerFactory::class);
        $managerFactory->create(['foo'])
            ->shouldBeCalled()
            ->willReturn($manager->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);
        $container->get(ClaimCheckerManagerFactory::class)
            ->willReturn($managerFactory->reveal());

        $factory = new ClaimCheckerManagerAbstractFactory();

        $this->assertTrue($factory->canCreate($container->reveal(), $requestedName));

        $service = $factory($container->reveal(), $requestedName);

        $this->assertSame($manager->reveal(), $service);
    }
}
