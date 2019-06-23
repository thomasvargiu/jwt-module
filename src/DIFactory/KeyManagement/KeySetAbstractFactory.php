<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\KeyManagement;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Jose\Component\Core\JWKSet;
use Jose\Component\KeyManagement\JKUFactory;
use Jose\Component\KeyManagement\X5UFactory;
use TMV\JWTModule\DIFactory\AbstractServiceFactory;
use TMV\JWTModule\Exception\InvalidArgumentException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class KeySetAbstractFactory extends AbstractServiceFactory
{
    private const SERVICE_TYPE_KEY = 'key_sets';

    /**
     * Create an object
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     *
     * @throws ServiceNotFoundException if unable to resolve the service
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service
     * @throws ContainerException if any other error occurs
     *
     * @return JWKSet
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): JWKSet
    {
        try {
            $keyConfig = $this->getServiceConfig($container, $requestedName);
        } catch (InvalidArgumentException $e) {
            throw new ServiceNotCreatedException('Unable to find service for ' . $requestedName, 0, $e);
        }

        $type = $keyConfig['type'] ?? null;
        $keyOptions = $keyConfig['options'] ?? [];

        switch ($type) {
            case 'jwkset':
                return JWKSet::createFromJson($keyOptions['value'] ?? '{}');
            case 'jku':
                return $container->get(JKUFactory::class)->loadFromUrl(
                    $keyOptions['url'] ?? '',
                    $keyOptions['headers'] ?? []
                );
            case 'x5u':
                return $container->get(X5UFactory::class)->loadFromUrl(
                    $keyOptions['url'] ?? '',
                    $keyOptions['headers'] ?? []
                );
        }

        throw new ServiceNotCreatedException('Invalid jwk set type ' . $type);
    }

    protected function getServiceTypeName(): string
    {
        return static::SERVICE_TYPE_KEY;
    }
}
