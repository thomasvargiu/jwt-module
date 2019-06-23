<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Encryption;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Jose\Component\Encryption\JWEBuilder;
use Jose\Component\Encryption\JWEBuilderFactory;
use TMV\JWTModule\DIFactory\AbstractServiceFactory;
use TMV\JWTModule\Exception\InvalidArgumentException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class JWEBuilderAbstractFactory extends AbstractServiceFactory
{
    private const SERVICE_TYPE_KEY = 'jwe_builder';

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
     * @return JWEBuilder
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): JWEBuilder
    {
        try {
            $keyConfig = $this->getServiceConfig($container, $requestedName);
        } catch (InvalidArgumentException $e) {
            throw new ServiceNotCreatedException('Unable to find service for ' . $requestedName);
        }

        $keyEncryptionAlgorithms = $keyConfig['key_encryption_algorithms'] ?? [];
        $contentEncryptionAlgorithms = $keyConfig['content_encryption_algorithms'] ?? [];
        $compressionMethods = $keyConfig['compression_methods'] ?? [];

        /** @var JWEBuilderFactory $factory */
        $factory = $container->get(JWEBuilderFactory::class);

        return $factory->create(
            $keyEncryptionAlgorithms,
            $contentEncryptionAlgorithms,
            $compressionMethods
        );
    }

    protected function getServiceTypeName(): string
    {
        return static::SERVICE_TYPE_KEY;
    }
}
