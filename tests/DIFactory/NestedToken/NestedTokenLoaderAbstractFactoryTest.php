<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\NestedToken;

use Interop\Container\ContainerInterface;
use Jose\Component\NestedToken\NestedTokenLoader;
use Jose\Component\NestedToken\NestedTokenLoaderFactory;
use PHPUnit\Framework\TestCase;
use TMV\JWTModule\DIFactory\NestedToken\NestedTokenLoaderAbstractFactory;

class NestedTokenLoaderAbstractFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $requestedName = 'jwt_module.nested_token_loader.loader1';

        $config = [
            'jwt_module' => [
                'nested_token_loader' => [
                    'loader1' => [
                        'jwe_serializers' => ['serializer1'],
                        'key_encryption_algorithms' => ['alg1'],
                        'content_encryption_algorithms' => ['alg2'],
                        'compression_methods' => ['compression1'],
                        'jwe_header_checkers' => ['checker1'],
                        'jws_serializers' => ['serializer2'],
                        'signature_algorithms' => ['alg3'],
                        'jws_header_checkers' => ['checker2'],
                    ],
                ],
            ],
        ];

        $loader = $this->prophesize(NestedTokenLoader::class);

        $loaderFactory = $this->prophesize(NestedTokenLoaderFactory::class);
        $loaderFactory->create(
            ['serializer1'],
            ['alg1'],
            ['alg2'],
            ['compression1'],
            ['checker1'],
            ['serializer2'],
            ['alg3'],
            ['checker2']
        )
            ->willReturn($loader->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);
        $container->get(NestedTokenLoaderFactory::class)
            ->willReturn($loaderFactory->reveal());

        $factory = new NestedTokenLoaderAbstractFactory();

        $this->assertTrue($factory->canCreate($container->reveal(), $requestedName));

        $service = $factory($container->reveal(), $requestedName);

        $this->assertSame($loader->reveal(), $service);
    }
}
