<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\KeyManagement;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Jose\Component\Core\JWK;
use Jose\Component\Core\JWKSet;
use Jose\Component\KeyManagement\JWKFactory;
use TMV\JWTModule\DIFactory\AbstractServiceFactory;
use TMV\JWTModule\Exception\InvalidArgumentException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class KeyAbstractFactory extends AbstractServiceFactory
{
    private const SERVICE_TYPE_KEY = 'keys';

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
     * @return JWK
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): JWK
    {
        try {
            $keyConfig = $this->getServiceConfig($container, $requestedName);
        } catch (InvalidArgumentException $e) {
            throw new ServiceNotCreatedException('Unable to find service for ' . $requestedName);
        }

        $type = $keyConfig['type'] ?? null;
        $keyOptions = $keyConfig['options'] ?? [];

        switch ($type) {
            case 'secret':
                return JWKFactory::createFromSecret(
                    $keyOptions['secret'] ?? '',
                    $keyOptions['additional_values'] ?? []
                );

            case 'jwk':
                $jwk = JWKFactory::createFromJsonObject($keyOptions['value'] ?? '');

                if (! $jwk instanceof JWK) {
                    throw new ServiceNotCreatedException('Invalid value key for service ' . $requestedName);
                }

                return $jwk;

            case 'certificate':
                return JWKFactory::createFromCertificateFile(
                    $keyOptions['path'] ?? '',
                    $keyOptions['additional_values'] ?? []
                );

            case 'x5c':
                return JWKFactory::createFromCertificate(
                    $keyOptions['value'] ?? '',
                    $keyOptions['additional_values'] ?? []
                );

            case 'file':
                return JWKFactory::createFromKeyFile(
                    $keyOptions['path'] ?? '',
                    $keyOptions['password'] ?? null,
                    $keyOptions['additional_values'] ?? []
                );

            case 'jwkset':
                $keySetService = $keyOptions['key_set'] ?? null;
                $keySet = $container->get($keySetService);

                if (! $keySet instanceof JWKSet) {
                    throw new ServiceNotCreatedException('Unable to get keyset ' . $keySetService);
                }

                return JWKFactory::createFromKeySet(
                    $keySet,
                    (int) ($keyOptions['index'] ?? 0)
                );
        }

        throw new ServiceNotCreatedException('Invalid jwk type ' . $type);
    }

    protected function getServiceTypeName(): string
    {
        return static::SERVICE_TYPE_KEY;
    }
}
