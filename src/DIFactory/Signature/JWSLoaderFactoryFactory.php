<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Signature;

use Jose\Component\Checker\HeaderCheckerManagerFactory;
use Jose\Component\Signature\JWSLoaderFactory;
use Jose\Component\Signature\JWSVerifierFactory;
use Jose\Component\Signature\Serializer\JWSSerializerManagerFactory;
use Psr\Container\ContainerInterface;

class JWSLoaderFactoryFactory
{
    public function __invoke(ContainerInterface $container): JWSLoaderFactory
    {
        return new JWSLoaderFactory(
            $container->get(JWSSerializerManagerFactory::class),
            $container->get(JWSVerifierFactory::class),
            $container->get(HeaderCheckerManagerFactory::class)
        );
    }
}
