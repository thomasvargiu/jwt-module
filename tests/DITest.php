<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest;

use Jose\Component\Checker\ClaimCheckerManager;
use Jose\Component\Checker\HeaderCheckerManager;
use Jose\Component\Core\JWK;
use Jose\Component\Core\JWKSet;
use Jose\Component\Encryption\JWEBuilder;
use Jose\Component\Encryption\JWEDecrypter;
use Jose\Component\Encryption\JWELoader;
use Jose\Component\Encryption\Serializer\JWESerializerManager;
use Jose\Component\NestedToken\NestedTokenBuilder;
use Jose\Component\NestedToken\NestedTokenLoader;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\JWSLoader;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use TMV\JWTModule\ConfigProvider;

class DITest extends TestCase
{
    private function getContainer(): ContainerInterface
    {
        /** @var ContainerInterface $container */
        $container = require __DIR__ . '/test_container.php';

        return $container;
    }

    /**
     * @dataProvider factoriesProvider
     *
     * @param string $serviceName
     */
    public function testDIFactories(string $serviceName): void
    {
        $container = $this->getContainer();
        $this->assertNotNull($container->get($serviceName), 'Unable to create service ' . $serviceName);
    }

    public function factoriesProvider(): array
    {
        $configProvider = new ConfigProvider();
        $dependencies = $configProvider->getDependencies();

        return \array_map(function (string $key) {
            return [$key];
        }, \array_keys($dependencies['factories']));
    }

    /**
     * @dataProvider abstractFactoryProvider
     *
     * @param string $serviceName
     * @param string $serviceInstance
     */
    public function testAbstractFactories(string $serviceName, string $serviceInstance): void
    {
        $container = $this->getContainer();

        $this->assertInstanceOf($serviceInstance, $container->get($serviceName));
    }

    public function abstractFactoryProvider(): array
    {
        return [
            ['jwt_module.header_checker.checker1', HeaderCheckerManager::class],
            ['jwt_module.claim_checker.checker1', ClaimCheckerManager::class],

            ['jwt_module.jws_loader.loader1', JWSLoader::class],
            ['jwt_module.jws_serializer.serializer1', JWSSerializerManager::class],
            ['jwt_module.jws_builder.builder1', JWSBuilder::class],
            ['jwt_module.jws_verifier.verifier1', JWSVerifier::class],

            ['jwt_module.jwe_loader.loader1', JWELoader::class],
            ['jwt_module.jwe_serializer.serializer1', JWESerializerManager::class],
            ['jwt_module.jwe_builder.builder1', JWEBuilder::class],
            ['jwt_module.jwe_decrypter.decrypter1', JWEDecrypter::class],

            ['jwt_module.nested_token_builder.builder1', NestedTokenBuilder::class],
            ['jwt_module.nested_token_loader.loader1', NestedTokenLoader::class],

            ['jwt_module.keys.key1', JWK::class],
            ['jwt_module.key_sets.set1', JWKSet::class],
        ];
    }
}
