<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\DIFactory\Signature;

use Interop\Container\ContainerInterface;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\JWSVerifierFactory;
use PHPUnit\Framework\TestCase;
use TMV\JWTModule\DIFactory\Signature\JWSVerifierAbstractFactory;

class JWSVerifierAbstractFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $requestedName = 'jwt_module.jws_verifier.service1';

        $config = [
            'jwt_module' => [
                'jws_verifier' => [
                    'service1' => [
                        'signature_algorithms' => ['alg1'],
                    ],
                ],
            ],
        ];

        $verifier = $this->prophesize(JWSVerifier::class);

        $verifierFactory = $this->prophesize(JWSVerifierFactory::class);
        $verifierFactory->create(['alg1'])
            ->willReturn($verifier->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);
        $container->get(JWSVerifierFactory::class)
            ->willReturn($verifierFactory->reveal());

        $factory = new JWSVerifierAbstractFactory();

        $this->assertTrue($factory->canCreate($container->reveal(), $requestedName));

        $service = $factory($container->reveal(), $requestedName);

        $this->assertSame($verifier->reveal(), $service);
    }
}
