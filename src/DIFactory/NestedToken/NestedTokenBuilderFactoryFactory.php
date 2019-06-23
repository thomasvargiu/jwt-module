<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\NestedToken;

use Jose\Component\Encryption\JWEBuilderFactory;
use Jose\Component\Encryption\Serializer\JWESerializerManagerFactory;
use Jose\Component\NestedToken\NestedTokenBuilderFactory;
use Jose\Component\Signature\JWSBuilderFactory;
use Jose\Component\Signature\Serializer\JWSSerializerManagerFactory;
use Psr\Container\ContainerInterface;

class NestedTokenBuilderFactoryFactory
{
    public function __invoke(ContainerInterface $container): NestedTokenBuilderFactory
    {
        return new NestedTokenBuilderFactory(
            $container->get(JWEBuilderFactory::class),
            $container->get(JWESerializerManagerFactory::class),
            $container->get(JWSBuilderFactory::class),
            $container->get(JWSSerializerManagerFactory::class)
        );
    }
}
