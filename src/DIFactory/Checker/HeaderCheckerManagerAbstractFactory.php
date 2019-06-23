<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Checker;

use Interop\Container\ContainerInterface;
use Jose\Component\Checker\HeaderCheckerManager;
use Jose\Component\Checker\HeaderCheckerManagerFactory;
use TMV\JWTModule\DIFactory\AbstractServiceFactory;
use TMV\JWTModule\Exception\InvalidArgumentException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class HeaderCheckerManagerAbstractFactory extends AbstractServiceFactory
{
    private const SERVICE_TYPE_KEY = 'header_checker';

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
     *
     * @return HeaderCheckerManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): HeaderCheckerManager
    {
        try {
            $keyConfig = $this->getServiceConfig($container, $requestedName);
        } catch (InvalidArgumentException $e) {
            throw new ServiceNotCreatedException('Unable to find service for ' . $requestedName);
        }

        $headers = $keyConfig['headers'] ?? [];

        /** @var HeaderCheckerManagerFactory $factory */
        $factory = $container->get(HeaderCheckerManagerFactory::class);

        return $factory->create($headers);
    }

    protected function getServiceTypeName(): string
    {
        return static::SERVICE_TYPE_KEY;
    }
}
