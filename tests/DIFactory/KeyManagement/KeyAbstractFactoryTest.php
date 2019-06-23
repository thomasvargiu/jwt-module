<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\KeyManagement;

use Interop\Container\ContainerInterface;
use Jose\Component\Core\JWK;
use Jose\Component\Core\JWKSet;
use PHPUnit\Framework\TestCase;
use TMV\JWTModule\DIFactory\KeyManagement\KeyAbstractFactory;

class KeyAbstractFactoryTest extends TestCase
{
    public function testInvokeWithSecretKey(): void
    {
        $requestedName = 'jwt_module.keys.key1';

        $config = [
            'jwt_module' => [
                'keys' => [
                    'key1' => [
                        'type' => 'secret',
                        'options' => [
                            'secret' => 'foo',
                            'additional_values' => ['foo' => 'bar'],
                        ],
                    ],
                ],
            ],
        ];

        $factory = new KeyAbstractFactory();

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);

        $jwk = $factory($container->reveal(), $requestedName);

        $this->assertInstanceOf(JWK::class, $jwk);
        $this->assertSame('bar', $jwk->get('foo'));
    }

    public function testInvokeWithJwkKey(): void
    {
        $requestedName = 'jwt_module.keys.key1';

        $config = [
            'jwt_module' => [
                'keys' => [
                    'key1' => [
                        'type' => 'jwk',
                        'options' => [
                            'value' => '{"foo":"bar","kty":"oct","k":"Zm9v"}',
                        ],
                    ],
                ],
            ],
        ];

        $factory = new KeyAbstractFactory();

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);

        $jwk = $factory($container->reveal(), $requestedName);

        $this->assertInstanceOf(JWK::class, $jwk);
        $this->assertSame('bar', $jwk->get('foo'));
    }

    public function testInvokeWithJwkSetKey(): void
    {
        $requestedName = 'jwt_module.keys.key1';

        $config = [
            'jwt_module' => [
                'keys' => [
                    'key1' => [
                        'type' => 'jwkset',
                        'options' => [
                            'key_set' => 'jwks_service',
                            'index' => 1,
                        ],
                    ],
                ],
            ],
        ];

        $jwks = JWKSet::createFromJson('{"keys":[{"foo":"bar1","kty":"oct","k":"Zm9v"},{"foo":"bar2","kty":"oct","k":"Zm9v"}]}');

        $factory = new KeyAbstractFactory();

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);
        $container->get('jwks_service')->willReturn($jwks);

        $jwk = $factory($container->reveal(), $requestedName);

        $this->assertInstanceOf(JWK::class, $jwk);
        $this->assertSame('bar2', $jwk->get('foo'));
    }
}
