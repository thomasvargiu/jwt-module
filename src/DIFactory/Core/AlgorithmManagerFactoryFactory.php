<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Core;

use Jose\Component\Core\Algorithm;
use Jose\Component\Core\AlgorithmManagerFactory;
use Psr\Container\ContainerInterface;
use TMV\JWTModule\Exception\InvalidArgumentException;

class AlgorithmManagerFactoryFactory
{
    public function __invoke(ContainerInterface $container): AlgorithmManagerFactory
    {
        $config = $container->get('config')['jwt_module']['algorithm_manager'] ?? [];

        $algorithmManagerFactory = new AlgorithmManagerFactory();

        $algorithms = $this->getAlgorithms($container, $config['algorithms'] ?? []);

        foreach ($algorithms as $alias => $algorithm) {
            $algorithmManagerFactory->add(! \is_string($alias) ? $algorithm->name() : $alias, $algorithm);
        }

        return $algorithmManagerFactory;
    }

    /**
     * @param ContainerInterface $container
     * @param string[]|Algorithm[] $algorithms
     *
     * @return Algorithm[]
     */
    private function getAlgorithms(ContainerInterface $container, array $algorithms): array
    {
        return \array_map(static function ($algorithm) use ($container) {
            if ($algorithm instanceof Algorithm) {
                return $algorithm;
            }

            $algorithm = $container->get($algorithm);

            if (! $algorithm instanceof Algorithm) {
                throw new InvalidArgumentException('Invalid algorithm');
            }

            return $algorithm;
        }, $algorithms);
    }
}
