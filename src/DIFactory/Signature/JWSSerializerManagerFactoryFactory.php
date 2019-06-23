<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Signature;

use Jose\Component\Signature\Serializer\JWSSerializer;
use Jose\Component\Signature\Serializer\JWSSerializerManagerFactory;
use Psr\Container\ContainerInterface;
use TMV\JWTModule\Exception\InvalidArgumentException;

class JWSSerializerManagerFactoryFactory
{
    private const MODULE_KEY = 'jwt_module';

    private const SERVICE_TYPE_KEY = 'jws_serializer_manager';

    public function __invoke(ContainerInterface $container): JWSSerializerManagerFactory
    {
        $config = $container->get('config')[static::MODULE_KEY][static::SERVICE_TYPE_KEY] ?? [];

        $serializers = $this->getSerializers($container, $config['serializers'] ?? []);

        $factory = new JWSSerializerManagerFactory();

        foreach ($serializers as $alias => $serializer) {
            $factory->add($serializer);
        }

        return $factory;
    }

    /**
     * @param ContainerInterface $container
     * @param string[]|JWSSerializer[] $serializers
     *
     * @return JWSSerializer[]
     */
    private function getSerializers(ContainerInterface $container, array $serializers): array
    {
        return \array_map(static function ($serializer) use ($container) {
            if ($serializer instanceof JWSSerializer) {
                return $serializer;
            }

            $serializer = $container->get($serializer);

            if (! $serializer instanceof JWSSerializer) {
                throw new InvalidArgumentException('Invalid serializer');
            }

            return $serializer;
        }, $serializers);
    }
}
