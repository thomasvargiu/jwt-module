<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Encryption;

use Jose\Component\Core\AlgorithmManagerFactory;
use Jose\Component\Encryption\Compression\CompressionMethodManagerFactory;
use Jose\Component\Encryption\JWEBuilderFactory;
use Psr\Container\ContainerInterface;

class JWEBuilderFactoryFactory
{
    public function __invoke(ContainerInterface $container): JWEBuilderFactory
    {
        /** @var AlgorithmManagerFactory $algorithmManagerFactory */
        $algorithmManagerFactory = $container->get(AlgorithmManagerFactory::class);
        /** @var CompressionMethodManagerFactory $compressionManagerFactory */
        $compressionManagerFactory = $container->get(CompressionMethodManagerFactory::class);

        return new JWEBuilderFactory(
            $algorithmManagerFactory,
            $compressionManagerFactory
        );
    }
}
