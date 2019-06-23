<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Encryption;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Jose\Component\Encryption\JWEDecrypter;
use Jose\Component\Encryption\JWEDecrypterFactory;
use TMV\JWTModule\DIFactory\AbstractServiceFactory;
use TMV\JWTModule\Exception\InvalidArgumentException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class JWEDecrypterAbstractFactory extends AbstractServiceFactory
{
    private const SERVICE_TYPE_KEY = 'jwe_decrypter';

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
     * @return JWEDecrypter
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): JWEDecrypter
    {
        try {
            $keyConfig = $this->getServiceConfig($container, $requestedName);
        } catch (InvalidArgumentException $e) {
            throw new ServiceNotCreatedException('Unable to find service for ' . $requestedName, 0, $e);
        }

        $keyEncryptionAlgorithms = $keyConfig['key_encryption_algorithms'] ?? [];
        $contentEncryptionAlgorithms = $keyConfig['content_encryption_algorithms'] ?? [];
        $compressionMethods = $keyConfig['compression_methods'] ?? [];

        /** @var JWEDecrypterFactory $factory */
        $factory = $container->get(JWEDecrypterFactory::class);

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
