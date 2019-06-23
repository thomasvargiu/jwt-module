<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Checker;

use Interop\Container\ContainerInterface;
use Jose\Component\Checker\HeaderChecker;
use Jose\Component\Checker\HeaderCheckerManagerFactory;
use Jose\Component\Checker\TokenTypeSupport;
use PHPUnit\Framework\TestCase;
use TMV\JWTModule\DIFactory\Checker\HeaderCheckerManagerFactoryFactory;

class HeaderCheckerManagerFactoryFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $checker1 = $this->prophesize(HeaderChecker::class);
        $checker2 = $this->prophesize(HeaderChecker::class);
        $checker3 = $this->prophesize(HeaderChecker::class);

        $checker3->supportedHeader()->willReturn('checker3');

        $tokenTypeSupport1 = $this->prophesize(TokenTypeSupport::class);
        $tokenTypeSupport2 = $this->prophesize(TokenTypeSupport::class);

        $config = [
            'jwt_module' => [
                'header_checker_manager' => [
                    'checkers' => [
                        'foo' => $checker1,
                        'bar' => 'checker2',
                    ],
                    'token_types' => [
                        $tokenTypeSupport1,
                        'tokenTypeSupport2',
                    ],
                ],
            ],
        ];

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);

        $container->get('checker2')->shouldBeCalled()->willReturn($checker2->reveal());
        $container->get('tokenTypeSupport2')->shouldBeCalled()->willReturn($tokenTypeSupport2->reveal());

        $factory = new HeaderCheckerManagerFactoryFactory();
        $service = $factory($container->reveal());

        $this->assertCount(2, $service->all());
        $this->assertSame($checker1->reveal(), $service->all()['foo'] ?? null);
        $this->assertSame($checker2->reveal(), $service->all()['bar'] ?? null);

        $this->assertInstanceOf(HeaderCheckerManagerFactory::class, $service);
    }
}
