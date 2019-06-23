<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\NestedToken;

use Jose\Component\Encryption\JWELoaderFactory;
use Jose\Component\NestedToken\NestedTokenLoaderFactory;
use Jose\Component\Signature\JWSLoaderFactory;
use Psr\Container\ContainerInterface;

class NestedTokenLoaderFactoryFactory
{
    public function __invoke(ContainerInterface $container): NestedTokenLoaderFactory
    {
        return new NestedTokenLoaderFactory(
            $container->get(JWELoaderFactory::class),
            $container->get(JWSLoaderFactory::class)
        );
    }
}
