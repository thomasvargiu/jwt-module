<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Signature;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\JWSVerifierFactory;
use TMV\JWTModule\DIFactory\AbstractServiceFactory;
use TMV\JWTModule\Exception\InvalidArgumentException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class JWSVerifierAbstractFactory extends AbstractServiceFactory
{
    private const SERVICE_TYPE_KEY = 'jws_verifier';

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
     * @return JWSVerifier
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): JWSVerifier
    {
        try {
            $keyConfig = $this->getServiceConfig($container, $requestedName);
        } catch (InvalidArgumentException $e) {
            throw new ServiceNotCreatedException('Unable to find service for ' . $requestedName);
        }

        $signatureAlgorithms = $keyConfig['signature_algorithms'] ?? [];

        /** @var JWSVerifierFactory $factory */
        $factory = $container->get(JWSVerifierFactory::class);

        return $factory->create($signatureAlgorithms);
    }

    protected function getServiceTypeName(): string
    {
        return static::SERVICE_TYPE_KEY;
    }
}
