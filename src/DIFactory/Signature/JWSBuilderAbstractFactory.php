<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Signature;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\JWSBuilderFactory;
use TMV\JWTModule\DIFactory\AbstractServiceFactory;
use TMV\JWTModule\Exception\InvalidArgumentException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class JWSBuilderAbstractFactory extends AbstractServiceFactory
{
    private const SERVICE_TYPE_KEY = 'jws_builder';

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
     * @return JWSBuilder
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): JWSBuilder
    {
        try {
            $keyConfig = $this->getServiceConfig($container, $requestedName);
        } catch (InvalidArgumentException $e) {
            throw new ServiceNotCreatedException('Unable to find service for ' . $requestedName);
        }

        $algorithms = $keyConfig['algorithms'] ?? [];

        /** @var JWSBuilderFactory $factory */
        $factory = $container->get(JWSBuilderFactory::class);

        return $factory->create($algorithms);
    }

    protected function getServiceTypeName(): string
    {
        return static::SERVICE_TYPE_KEY;
    }
}
