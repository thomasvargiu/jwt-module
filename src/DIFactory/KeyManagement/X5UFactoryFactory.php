<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\KeyManagement;

use Jose\Component\KeyManagement\X5UFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class X5UFactoryFactory
{
    public function __invoke(ContainerInterface $container): X5UFactory
    {
        $clientName = $container->get('config')['jwt_module']['jku_factory']['http_client'] ?? ClientInterface::class;

        return new X5UFactory(
            $container->get($clientName),
            $container->get(RequestFactoryInterface::class)
        );
    }
}
