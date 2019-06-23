<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Encryption;

use Interop\Container\ContainerInterface;
use Jose\Component\Encryption\JWELoader;
use Jose\Component\Encryption\JWELoaderFactory;
use PHPUnit\Framework\TestCase;
use TMV\JWTModule\DIFactory\Encryption\JWELoaderAbstractFactory;

class JWELoaderAbstractFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $requestedName = 'jwt_module.jwe_loader.loader1';

        $config = [
            'jwt_module' => [
                'jwe_loader' => [
                    'loader1' => [
                        'serializers' => ['serializer1'],
                        'key_encryption_algorithms' => ['alg1'],
                        'content_encryption_algorithms' => ['alg2'],
                        'compression_methods' => ['compression1'],
                        'header_checkers' => ['checker1'],
                    ],
                ],
            ],
        ];

        $loader = $this->prophesize(JWELoader::class);

        $loaderFactory = $this->prophesize(JWELoaderFactory::class);
        $loaderFactory->create(['serializer1'], ['alg1'], ['alg2'], ['compression1'], ['checker1'])
            ->willReturn($loader->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);
        $container->get(JWELoaderFactory::class)
            ->willReturn($loaderFactory->reveal());

        $factory = new JWELoaderAbstractFactory();

        $this->assertTrue($factory->canCreate($container->reveal(), $requestedName));

        $service = $factory($container->reveal(), $requestedName);

        $this->assertSame($loader->reveal(), $service);
    }
}
