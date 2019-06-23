<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Encryption;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Jose\Component\Encryption\JWELoader;
use Jose\Component\Encryption\JWELoaderFactory;
use TMV\JWTModule\DIFactory\AbstractServiceFactory;
use TMV\JWTModule\Exception\InvalidArgumentException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class JWELoaderAbstractFactory extends AbstractServiceFactory
{
    private const SERVICE_TYPE_KEY = 'jwe_loader';

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
     * @return JWELoader
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): JWELoader
    {
        try {
            $keyConfig = $this->getServiceConfig($container, $requestedName);
        } catch (InvalidArgumentException $e) {
            throw new ServiceNotCreatedException('Unable to find service for ' . $requestedName);
        }

        /** @var JWELoaderFactory $factory */
        $factory = $container->get(JWELoaderFactory::class);

        return $factory->create(
            $keyConfig['serializers'] ?? [],
            $keyConfig['key_encryption_algorithms'] ?? [],
            $keyConfig['content_encryption_algorithms'] ?? [],
            $keyConfig['compression_methods'] ?? [],
            $keyConfig['header_checkers'] ?? []
        );
    }

    protected function getServiceTypeName(): string
    {
        return static::SERVICE_TYPE_KEY;
    }
}
