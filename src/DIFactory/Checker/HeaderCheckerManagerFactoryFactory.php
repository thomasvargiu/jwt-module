<?php

declare(strict_types=1);

namespace TMV\JWTModule\DIFactory\Checker;

use Jose\Component\Checker\HeaderChecker;
use Jose\Component\Checker\HeaderCheckerManagerFactory;
use Jose\Component\Checker\TokenTypeSupport;
use Psr\Container\ContainerInterface;
use TMV\JWTModule\Exception\InvalidArgumentException;

class HeaderCheckerManagerFactoryFactory
{
    private const MODULE_KEY = 'jwt_module';

    private const SERVICE_TYPE_KEY = 'header_checker_manager';

    public function __invoke(ContainerInterface $container): HeaderCheckerManagerFactory
    {
        $config = $container->get('config')[static::MODULE_KEY][static::SERVICE_TYPE_KEY] ?? [];

        $checkers = $this->getCheckers($container, $config['checkers'] ?? []);
        $tokenTypes = $this->getTokenType($container, $config['token_types'] ?? []);

        $factory = new HeaderCheckerManagerFactory();

        foreach ($checkers as $alias => $checker) {
            if (! \is_string($alias)) {
                throw new InvalidArgumentException('Invalid alias for header checker');
            }

            $factory->add($alias, $checker);
        }

        foreach ($tokenTypes as $tokenType) {
            $factory->addTokenTypeSupport($tokenType);
        }

        return $factory;
    }

    /**
     * @param ContainerInterface $container
     * @param string[]|HeaderChecker[] $checkers
     *
     * @return HeaderChecker[]
     */
    private function getCheckers(ContainerInterface $container, array $checkers): array
    {
        return \array_map(static function ($checker) use ($container) {
            if ($checker instanceof HeaderChecker) {
                return $checker;
            }

            $checkerInstance = $container->get($checker);

            if (! $checkerInstance instanceof HeaderChecker) {
                throw new InvalidArgumentException('Invalid header checker');
            }

            return $checkerInstance;
        }, $checkers);
    }

    /**
     * @param ContainerInterface $container
     * @param string[]|TokenTypeSupport[] $tokenTypes
     *
     * @return TokenTypeSupport[]
     */
    private function getTokenType(ContainerInterface $container, array $tokenTypes): array
    {
        return \array_map(static function ($tokenType) use ($container) {
            if ($tokenType instanceof TokenTypeSupport) {
                return $tokenType;
            }

            $tokenType = $container->get($tokenType);

            if (! $tokenType instanceof TokenTypeSupport) {
                throw new InvalidArgumentException('Invalid token type');
            }

            return $tokenType;
        }, $tokenTypes);
    }
}
