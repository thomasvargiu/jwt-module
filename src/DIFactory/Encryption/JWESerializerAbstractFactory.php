<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Encryption;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Jose\Component\Encryption\Serializer\JWESerializerManager;
use Jose\Component\Encryption\Serializer\JWESerializerManagerFactory;
use TMV\JWTModule\DIFactory\AbstractServiceFactory;
use TMV\JWTModule\Exception\InvalidArgumentException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class JWESerializerAbstractFactory extends AbstractServiceFactory
{
    private const SERVICE_TYPE_KEY = 'jwe_serializer';

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
     * @return JWESerializerManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): JWESerializerManager
    {
        try {
            $keyConfig = $this->getServiceConfig($container, $requestedName);
        } catch (InvalidArgumentException $e) {
            throw new ServiceNotCreatedException('Unable to find service for ' . $requestedName);
        }

        $serializers = $keyConfig['serializers'] ?? [];

        /** @var JWESerializerManagerFactory $factory */
        $factory = $container->get(JWESerializerManagerFactory::class);

        return $factory->create($serializers);
    }

    protected function getServiceTypeName(): string
    {
        return static::SERVICE_TYPE_KEY;
    }
}
