<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Signature;

use Jose\Component\Core\AlgorithmManagerFactory;
use Jose\Component\Signature\JWSVerifierFactory;
use Psr\Container\ContainerInterface;

class JWSVerifierFactoryFactory
{
    public function __invoke(ContainerInterface $container): JWSVerifierFactory
    {
        /** @var AlgorithmManagerFactory $algorithmManagerFactory */
        $algorithmManagerFactory = $container->get(AlgorithmManagerFactory::class);

        return new JWSVerifierFactory($algorithmManagerFactory);
    }
}
