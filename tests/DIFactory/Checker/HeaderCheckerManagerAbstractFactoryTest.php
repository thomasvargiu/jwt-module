<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Checker;

use Interop\Container\ContainerInterface;
use Jose\Component\Checker\HeaderCheckerManager;
use Jose\Component\Checker\HeaderCheckerManagerFactory;
use PHPUnit\Framework\TestCase;
use TMV\JWTModule\DIFactory\Checker\HeaderCheckerManagerAbstractFactory;

class HeaderCheckerManagerAbstractFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $requestedName = 'jwt_module.header_checker.checker1';

        $config = [
            'jwt_module' => [
                'header_checker' => [
                    'checker1' => [
                        'headers' => [
                            'foo',
                        ],
                    ],
                ],
            ],
        ];

        $manager = $this->prophesize(HeaderCheckerManager::class);

        $managerFactory = $this->prophesize(HeaderCheckerManagerFactory::class);
        $managerFactory->create(['foo'])
            ->shouldBeCalled()
            ->willReturn($manager->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);
        $container->get(HeaderCheckerManagerFactory::class)
            ->willReturn($managerFactory->reveal());

        $factory = new HeaderCheckerManagerAbstractFactory();

        $this->assertTrue($factory->canCreate($container->reveal(), $requestedName));

        $service = $factory($container->reveal(), $requestedName);

        $this->assertSame($manager->reveal(), $service);
    }
}
