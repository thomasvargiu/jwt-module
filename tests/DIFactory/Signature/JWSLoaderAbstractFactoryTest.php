<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Signature;

use Interop\Container\ContainerInterface;
use Jose\Component\Signature\JWSLoader;
use Jose\Component\Signature\JWSLoaderFactory;
use PHPUnit\Framework\TestCase;
use TMV\JWTModule\DIFactory\Signature\JWSLoaderAbstractFactory;

class JWSLoaderAbstractFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $requestedName = 'jwt_module.jws_loader.loader1';

        $config = [
            'jwt_module' => [
                'jws_loader' => [
                    'loader1' => [
                        'serializers' => ['serializer1'],
                        'signature_algorithms' => ['alg1'],
                        'header_checkers' => ['checker1'],
                    ],
                ],
            ],
        ];

        $loader = $this->prophesize(JWSLoader::class);

        $loaderFactory = $this->prophesize(JWSLoaderFactory::class);
        $loaderFactory->create(['serializer1'], ['alg1'], ['checker1'])
            ->willReturn($loader->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);
        $container->get(JWSLoaderFactory::class)
            ->willReturn($loaderFactory->reveal());

        $factory = new JWSLoaderAbstractFactory();

        $this->assertTrue($factory->canCreate($container->reveal(), $requestedName));

        $service = $factory($container->reveal(), $requestedName);

        $this->assertSame($loader->reveal(), $service);
    }
}
