<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Checker;

use Jose\Component\Checker\ClaimChecker;
use Jose\Component\Checker\ClaimCheckerManagerFactory;
use Psr\Container\ContainerInterface;
use TMV\JWTModule\Exception\InvalidArgumentException;

class ClaimCheckerManagerFactoryFactory
{
    private const MODULE_KEY = 'jwt_module';

    private const SERVICE_TYPE_KEY = 'claim_checker_manager';

    public function __invoke(ContainerInterface $container): ClaimCheckerManagerFactory
    {
        $config = $container->get('config')[static::MODULE_KEY][static::SERVICE_TYPE_KEY] ?? [];

        $checkers = $this->getCheckers($container, $config['checkers'] ?? []);

        $factory = new ClaimCheckerManagerFactory();

        foreach ($checkers as $alias => $checker) {
            if (! \is_string($alias)) {
                throw new InvalidArgumentException('Invalid alias for claim checker');
            }

            $factory->add($alias, $checker);
        }

        return $factory;
    }

    /**
     * @param ContainerInterface $container
     * @param string[]|ClaimChecker[] $checkers
     *
     * @return ClaimChecker[]
     */
    private function getCheckers(ContainerInterface $container, array $checkers): array
    {
        return \array_map(static function ($checker) use ($container) {
            if ($checker instanceof ClaimChecker) {
                return $checker;
            }

            $checker = $container->get($checker);

            if (! $checker instanceof ClaimChecker) {
                throw new InvalidArgumentException('Invalid claim checker');
            }

            return $checker;
        }, $checkers);
    }
}
