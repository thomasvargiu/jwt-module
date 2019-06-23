<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\NestedToken;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Jose\Component\NestedToken\NestedTokenBuilder;
use Jose\Component\NestedToken\NestedTokenBuilderFactory;
use TMV\JWTModule\DIFactory\AbstractServiceFactory;
use TMV\JWTModule\Exception\InvalidArgumentException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class NestedTokenBuilderAbstractFactory extends AbstractServiceFactory
{
    private const SERVICE_TYPE_KEY = 'nested_token_builder';

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
     * @return NestedTokenBuilder
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): NestedTokenBuilder
    {
        try {
            $keyConfig = $this->getServiceConfig($container, $requestedName);
        } catch (InvalidArgumentException $e) {
            throw new ServiceNotCreatedException('Unable to find service for ' . $requestedName);
        }

        /** @var NestedTokenBuilderFactory $factory */
        $factory = $container->get(NestedTokenBuilderFactory::class);

        return $factory->create(
            $keyConfig['jwe_serializers'] ?? [],
            $keyConfig['key_encryption_algorithms'] ?? [],
            $keyConfig['content_encryption_algorithms'] ?? [],
            $keyConfig['compression_methods'] ?? [],
            $keyConfig['jws_serializers'] ?? [],
            $keyConfig['signature_algorithms'] ?? []
        );
    }

    protected function getServiceTypeName(): string
    {
        return static::SERVICE_TYPE_KEY;
    }
}
