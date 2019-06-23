<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Checker;

use Interop\Container\ContainerInterface;
use Jose\Component\Checker\ClaimCheckerManager;
use Jose\Component\Checker\ClaimCheckerManagerFactory;
use TMV\JWTModule\DIFactory\AbstractServiceFactory;
use TMV\JWTModule\Exception\InvalidArgumentException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class ClaimCheckerManagerAbstractFactory extends AbstractServiceFactory
{
    private const SERVICE_TYPE_KEY = 'claim_checker';

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
     * @return ClaimCheckerManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ClaimCheckerManager
    {
        try {
            $keyConfig = $this->getServiceConfig($container, $requestedName);
        } catch (InvalidArgumentException $e) {
            throw new ServiceNotCreatedException('Unable to find service for ' . $requestedName);
        }

        $claims = $keyConfig['claims'] ?? [];

        /** @var ClaimCheckerManagerFactory $factory */
        $factory = $container->get(ClaimCheckerManagerFactory::class);

        return $factory->create($claims);
    }

    protected function getServiceTypeName(): string
    {
        return static::SERVICE_TYPE_KEY;
    }
}
