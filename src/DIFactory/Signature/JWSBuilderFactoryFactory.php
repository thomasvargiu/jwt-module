<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Signature;

use Jose\Component\Core\AlgorithmManagerFactory;
use Jose\Component\Signature\JWSBuilderFactory;
use Psr\Container\ContainerInterface;

class JWSBuilderFactoryFactory
{
    public function __invoke(ContainerInterface $container): JWSBuilderFactory
    {
        /** @var AlgorithmManagerFactory $algorithmManagerFactory */
        $algorithmManagerFactory = $container->get(AlgorithmManagerFactory::class);

        return new JWSBuilderFactory($algorithmManagerFactory);
    }
}
