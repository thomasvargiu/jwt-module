<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Encryption;

use Jose\Component\Core\AlgorithmManagerFactory;
use Jose\Component\Encryption\Compression\CompressionMethodManagerFactory;
use Jose\Component\Encryption\JWEDecrypterFactory;
use Psr\Container\ContainerInterface;

class JWEDecrypterFactoryFactory
{
    public function __invoke(ContainerInterface $container): JWEDecrypterFactory
    {
        $algorithmManagerFactory = $container->get(AlgorithmManagerFactory::class);
        $compressionMethodManagerFactory = $container->get(CompressionMethodManagerFactory::class);

        return new JWEDecrypterFactory(
            $algorithmManagerFactory,
            $compressionMethodManagerFactory
        );
    }
}
