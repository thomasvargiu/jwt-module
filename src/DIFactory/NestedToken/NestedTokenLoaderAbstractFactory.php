<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\NestedToken;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Jose\Component\NestedToken\NestedTokenLoader;
use Jose\Component\NestedToken\NestedTokenLoaderFactory;
use TMV\JWTModule\DIFactory\AbstractServiceFactory;
use TMV\JWTModule\Exception\InvalidArgumentException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class NestedTokenLoaderAbstractFactory extends AbstractServiceFactory
{
    private const SERVICE_TYPE_KEY = 'nested_token_loader';

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
     * @return NestedTokenLoader
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): NestedTokenLoader
    {
        try {
            $keyConfig = $this->getServiceConfig($container, $requestedName);
        } catch (InvalidArgumentException $e) {
            throw new ServiceNotCreatedException('Unable to find service for ' . $requestedName);
        }

        /** @var NestedTokenLoaderFactory $factory */
        $factory = $container->get(NestedTokenLoaderFactory::class);

        return $factory->create(
            $keyConfig['jwe_serializers'] ?? [],
            $keyConfig['key_encryption_algorithms'] ?? [],
            $keyConfig['content_encryption_algorithms'] ?? [],
            $keyConfig['compression_methods'] ?? [],
            $keyConfig['jwe_header_checkers'] ?? [],
            $keyConfig['jws_serializers'] ?? [],
            $keyConfig['signature_algorithms'] ?? [],
            $keyConfig['jws_header_checkers'] ?? []
        );
    }

    protected function getServiceTypeName(): string
    {
        return static::SERVICE_TYPE_KEY;
    }
}
