<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Encryption\Compression;

use Jose\Component\Encryption\Compression\CompressionMethod;
use Jose\Component\Encryption\Compression\CompressionMethodManagerFactory;
use Psr\Container\ContainerInterface;
use TMV\JWTModule\Exception\InvalidArgumentException;

class CompressionMethodManagerFactoryFactory
{
    private const MODULE_KEY = 'jwt_module';

    private const SERVICE_TYPE_KEY = 'compression_method_manager';

    public function __invoke(ContainerInterface $container): CompressionMethodManagerFactory
    {
        $config = $container->get('config')[static::MODULE_KEY][static::SERVICE_TYPE_KEY] ?? [];

        $compressionMethods = $this->getCompressionMethods($container, $config['compression_methods'] ?? []);

        $factory = new CompressionMethodManagerFactory();

        foreach ($compressionMethods as $alias => $compression) {
            $factory->add(! \is_string($alias) ? $compression->name() : $alias, $compression);
        }

        return $factory;
    }

    /**
     * @param ContainerInterface $container
     * @param string[]|CompressionMethod[] $serializers
     *
     * @return CompressionMethod[]
     */
    private function getCompressionMethods(ContainerInterface $container, array $serializers): array
    {
        return \array_map(static function ($serializer) use ($container) {
            if ($serializer instanceof CompressionMethod) {
                return $serializer;
            }

            $serializer = $container->get($serializer);

            if (! $serializer instanceof CompressionMethod) {
                throw new InvalidArgumentException('Invalid jwe compression method');
            }

            return $serializer;
        }, $serializers);
    }
}
