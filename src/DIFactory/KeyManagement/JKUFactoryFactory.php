<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\KeyManagement;

use Jose\Component\KeyManagement\JKUFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class JKUFactoryFactory
{
    public function __invoke(ContainerInterface $container): JKUFactory
    {
        $clientName = $container->get('config')['jwt_module']['jku_factory']['http_client'] ?? ClientInterface::class;

        return new JKUFactory(
            $container->get($clientName),
            $container->get(RequestFactoryInterface::class)
        );
    }
}
