<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Signature;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Jose\Component\Signature\JWSLoader;
use Jose\Component\Signature\JWSLoaderFactory;
use TMV\JWTModule\DIFactory\AbstractServiceFactory;
use TMV\JWTModule\Exception\InvalidArgumentException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class JWSLoaderAbstractFactory extends AbstractServiceFactory
{
    private const SERVICE_TYPE_KEY = 'jws_loader';

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
     * @return JWSLoader
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): JWSLoader
    {
        try {
            $keyConfig = $this->getServiceConfig($container, $requestedName);
        } catch (InvalidArgumentException $e) {
            throw new ServiceNotCreatedException('Unable to find service for ' . $requestedName);
        }

        /** @var JWSLoaderFactory $factory */
        $factory = $container->get(JWSLoaderFactory::class);

        return $factory->create(
            $keyConfig['serializers'] ?? [],
            $keyConfig['signature_algorithms'] ?? [],
            $keyConfig['header_checkers'] ?? []
        );
    }

    protected function getServiceTypeName(): string
    {
        return static::SERVICE_TYPE_KEY;
    }
}
