<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\KeyManagement;

use Interop\Container\ContainerInterface;
use Jose\Component\Core\JWKSet;
use Jose\Component\KeyManagement\JKUFactory;
use Jose\Component\KeyManagement\X5UFactory;
use PHPUnit\Framework\TestCase;
use TMV\JWTModule\DIFactory\KeyManagement\KeySetAbstractFactory;

class KeySetAbstractFactoryTest extends TestCase
{
    public function testInvokeWithJwks(): void
    {
        $requestedName = 'jwt_module.key_sets.set1';

        $config = [
            'jwt_module' => [
                'key_sets' => [
                    'set1' => [
                        'type' => 'jwkset',
                        'options' => [
                            'value' => '{"keys":[{"foo":"bar1","kty":"oct","k":"Zm9v"},{"foo":"bar2","kty":"oct","k":"Zm9v"}]}',
                        ],
                    ],
                ],
            ],
        ];

        $factory = new KeySetAbstractFactory();

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);

        $jwks = $factory($container->reveal(), $requestedName);

        $this->assertInstanceOf(JWKSet::class, $jwks);
        $this->assertSame(2, $jwks->count());
    }

    public function testInvokeWithJku(): void
    {
        $requestedName = 'jwt_module.key_sets.set1';

        $config = [
            'jwt_module' => [
                'key_sets' => [
                    'set1' => [
                        'type' => 'jku',
                        'options' => [
                            'url' => 'https://example.com',
                            'headers' => [
                                'foo' => 'bar',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $factory = new KeySetAbstractFactory();

        $jwks = $this->prophesize(JWKSet::class);

        $jkuFactory = $this->prophesize(JKUFactory::class);
        $jkuFactory->loadFromUrl('https://example.com', ['foo' => 'bar'])
            ->shouldBeCalled()
            ->willReturn($jwks->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);
        $container->get(JKUFactory::class)->willReturn($jkuFactory->reveal());

        $result = $factory($container->reveal(), $requestedName);

        $this->assertInstanceOf(JWKSet::class, $result);
        $this->assertSame($jwks->reveal(), $result);
    }

    public function testInvokeWithX5u(): void
    {
        $requestedName = 'jwt_module.key_sets.set1';

        $config = [
            'jwt_module' => [
                'key_sets' => [
                    'set1' => [
                        'type' => 'x5u',
                        'options' => [
                            'url' => 'https://example.com',
                            'headers' => [
                                'foo' => 'bar',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $factory = new KeySetAbstractFactory();

        $jwks = $this->prophesize(JWKSet::class);

        $x5uFactory = $this->prophesize(X5UFactory::class);
        $x5uFactory->loadFromUrl('https://example.com', ['foo' => 'bar'])
            ->shouldBeCalled()
            ->willReturn($jwks->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);
        $container->get(X5UFactory::class)->willReturn($x5uFactory->reveal());

        $result = $factory($container->reveal(), $requestedName);

        $this->assertInstanceOf(JWKSet::class, $result);
        $this->assertSame($jwks->reveal(), $result);
    }
}
