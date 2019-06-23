<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Encryption;

use Jose\Component\Checker\HeaderCheckerManagerFactory;
use Jose\Component\Encryption\JWEDecrypterFactory;
use Jose\Component\Encryption\JWELoaderFactory;
use Jose\Component\Encryption\Serializer\JWESerializerManagerFactory;
use Psr\Container\ContainerInterface;

class JWELoaderFactoryFactory
{
    public function __invoke(ContainerInterface $container): JWELoaderFactory
    {
        return new JWELoaderFactory(
            $container->get(JWESerializerManagerFactory::class),
            $container->get(JWEDecrypterFactory::class),
            $container->get(HeaderCheckerManagerFactory::class)
        );
    }
}
