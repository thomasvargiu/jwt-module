<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Encryption;

use Interop\Container\ContainerInterface;
use Jose\Component\Encryption\JWEDecrypter;
use Jose\Component\Encryption\JWEDecrypterFactory;
use PHPUnit\Framework\TestCase;
use TMV\JWTModule\DIFactory\Encryption\JWEDecrypterAbstractFactory;

class JWEDecrypterAbstractFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $requestedName = 'jwt_module.jwe_decrypter.service1';

        $config = [
            'jwt_module' => [
                'jwe_decrypter' => [
                    'service1' => [
                        'key_encryption_algorithms' => ['alg1'],
                        'content_encryption_algorithms' => ['alg2'],
                        'compression_methods' => ['compression1'],
                    ],
                ],
            ],
        ];

        $decrypter = $this->prophesize(JWEDecrypter::class);

        $decrypterFactory = $this->prophesize(JWEDecrypterFactory::class);
        $decrypterFactory->create(['alg1'], ['alg2'], ['compression1'])
            ->willReturn($decrypter->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);
        $container->get(JWEDecrypterFactory::class)
            ->willReturn($decrypterFactory->reveal());

        $factory = new JWEDecrypterAbstractFactory();

        $this->assertTrue($factory->canCreate($container->reveal(), $requestedName));

        $service = $factory($container->reveal(), $requestedName);

        $this->assertSame($decrypter->reveal(), $service);
    }
}
