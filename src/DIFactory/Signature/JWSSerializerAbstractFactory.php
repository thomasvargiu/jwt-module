<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Signature;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use Jose\Component\Signature\Serializer\JWSSerializerManagerFactory;
use TMV\JWTModule\DIFactory\AbstractServiceFactory;
use TMV\JWTModule\Exception\InvalidArgumentException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class JWSSerializerAbstractFactory extends AbstractServiceFactory
{
    private const SERVICE_TYPE_KEY = 'jws_serializer';

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
     * @return JWSSerializerManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): JWSSerializerManager
    {
        try {
            $keyConfig = $this->getServiceConfig($container, $requestedName);
        } catch (InvalidArgumentException $e) {
            throw new ServiceNotCreatedException('Unable to find service for ' . $requestedName);
        }

        $serializers = $keyConfig['serializers'] ?? [];

        /** @var JWSSerializerManagerFactory $factory */
        $factory = $container->get(JWSSerializerManagerFactory::class);

        return $factory->create($serializers);
    }

    protected function getServiceTypeName(): string
    {
        return static::SERVICE_TYPE_KEY;
    }
}
