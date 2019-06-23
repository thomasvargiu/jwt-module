<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Encryption;

use Jose\Component\Encryption\Serializer\JWESerializer;
use Jose\Component\Encryption\Serializer\JWESerializerManagerFactory;
use Psr\Container\ContainerInterface;
use TMV\JWTModule\Exception\InvalidArgumentException;

class JWESerializerManagerFactoryFactory
{
    private const MODULE_KEY = 'jwt_module';

    private const SERVICE_TYPE_KEY = 'jwe_serializer_manager';

    public function __invoke(ContainerInterface $container): JWESerializerManagerFactory
    {
        $config = $container->get('config')[static::MODULE_KEY][static::SERVICE_TYPE_KEY] ?? [];

        $serializers = $this->getSerializers($container, $config['serializers'] ?? []);

        $factory = new JWESerializerManagerFactory();

        foreach ($serializers as $alias => $serializer) {
            $factory->add($serializer);
        }

        return $factory;
    }

    /**
     * @param ContainerInterface $container
     * @param string[]|JWESerializer[] $serializers
     *
     * @return JWESerializer[]
     */
    private function getSerializers(ContainerInterface $container, array $serializers): array
    {
        return \array_map(static function ($serializer) use ($container) {
            if ($serializer instanceof JWESerializer) {
                return $serializer;
            }

            $serializer = $container->get($serializer);

            if (! $serializer instanceof JWESerializer) {
                throw new InvalidArgumentException('Invalid jwe serializer');
            }

            return $serializer;
        }, $serializers);
    }
}
