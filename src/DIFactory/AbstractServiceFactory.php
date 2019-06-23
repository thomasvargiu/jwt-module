<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory;

use Interop\Container\ContainerInterface;
use TMV\JWTModule\Exception\InvalidArgumentException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

abstract class AbstractServiceFactory implements AbstractFactoryInterface
{
    protected const MODULE_KEY = 'jwt_module';

    abstract protected function getServiceTypeName(): string;

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     *
     * @throws InvalidArgumentException
     *
     * @return array
     */
    protected function getServiceConfig(ContainerInterface $container, string $requestedName): array
    {
        if (0 !== \strpos($requestedName, \implode('.', [static::MODULE_KEY, $this->getServiceTypeName(), '']))) {
            throw new InvalidArgumentException('Invalid service name');
        }

        /** @var string[] $exploded */
        $exploded = \explode('.', $requestedName);
        $serviceName = $exploded[2] ?? null;

        if (! $serviceName) {
            throw new InvalidArgumentException('Invalid service name');
        }

        $keyConfig = $container->get('config')[static::MODULE_KEY][$this->getServiceTypeName()][$serviceName] ?? null;

        if (! \is_array($keyConfig)) {
            throw new InvalidArgumentException('Invalid service name');
        }

        return $keyConfig;
    }

    /**
     * Can the factory create an instance for the service?
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     *
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName): bool
    {
        try {
            $this->getServiceConfig($container, $requestedName);

            return true;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }
}
