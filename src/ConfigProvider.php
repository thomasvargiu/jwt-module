<?php

declare(strict_types=1);

namespace TMV\JWTModule;

use Jose\Component\Checker\ClaimCheckerManagerFactory;
use Jose\Component\Checker\HeaderCheckerManagerFactory;
use Jose\Component\Core\AlgorithmManagerFactory;
use Jose\Component\Encryption\Algorithm\ContentEncryption;
use Jose\Component\Encryption\Algorithm\KeyEncryption;
use Jose\Component\Encryption\Compression\CompressionMethodManagerFactory;
use Jose\Component\Encryption\JWEBuilderFactory;
use Jose\Component\Encryption\JWEDecrypterFactory;
use Jose\Component\Encryption\JWELoaderFactory;
use Jose\Component\Encryption\JWETokenSupport;
use Jose\Component\Encryption\Serializer\JWESerializerManagerFactory;
use Jose\Component\KeyManagement\JKUFactory;
use Jose\Component\KeyManagement\X5UFactory;
use Jose\Component\NestedToken\NestedTokenBuilderFactory;
use Jose\Component\NestedToken\NestedTokenLoaderFactory;
use Jose\Component\Signature\Algorithm as SignatureAlgorithm;
use Jose\Component\Signature\JWSBuilderFactory;
use Jose\Component\Signature\JWSLoaderFactory;
use Jose\Component\Signature\JWSTokenSupport;
use Jose\Component\Signature\JWSVerifierFactory;
use Jose\Component\Signature\Serializer\JWSSerializerManagerFactory;
use Psr\Http\Client\ClientInterface;
use Zend\ServiceManager\Factory\InvokableFactory;

class ConfigProvider
{
    private function filterExistingClasses(array $services): array
    {
        return \array_filter($services, '\class_exists');
    }

    private function getAlgorithms(): array
    {
        $algorithms = [
            'A128CBC-HS256' => ContentEncryption\A128CBCHS256::class,
            'A192CBC-HS384' => ContentEncryption\A192CBCHS384::class,
            'A256CBC-HS512' => ContentEncryption\A256CBCHS512::class,
            'A128GCM' => ContentEncryption\A128GCM::class,
            'A192GCM' => ContentEncryption\A192GCM::class,
            'A256GCM' => ContentEncryption\A256GCM::class,
            'A128GCMKW' => KeyEncryption\A128GCMKW::class,
            'A192GCMKW' => KeyEncryption\A192GCMKW::class,
            'A256GCMKW' => KeyEncryption\A256GCMKW::class,
            'A128KW' => KeyEncryption\A128KW::class,
            'A192KW' => KeyEncryption\A192KW::class,
            'A256KW' => KeyEncryption\A256KW::class,
            'dir' => KeyEncryption\Dir::class,
            'ECDH-ES' => KeyEncryption\ECDHES::class,
            'ECDH-ES+A128KW' => KeyEncryption\ECDHESA128KW::class,
            'ECDH-ES+A192KW' => KeyEncryption\ECDHESA192KW::class,
            'ECDH-ES+A256KW' => KeyEncryption\ECDHESA256KW::class,
            'A128CCM-16-64' => ContentEncryption\A128CCM_16_64::class,
            'A128CCM-16-128' => ContentEncryption\A128CCM_16_128::class,
            'A128CCM-64-64' => ContentEncryption\A128CCM_64_64::class,
            'A128CCM-64-128' => ContentEncryption\A128CCM_64_128::class,
            'A256CCM-16-64' => ContentEncryption\A256CCM_64_64::class,
            'A256CCM-16-128' => ContentEncryption\A256CCM_64_128::class,
            'A128CTR' => KeyEncryption\A128CTR::class,
            'A192CTR' => KeyEncryption\A192CTR::class,
            'A256CTR' => KeyEncryption\A256CTR::class,
            'RSA-OAEP-384' => KeyEncryption\RSAOAEP384::class,
            'RSA-OAEP-512' => KeyEncryption\RSAOAEP512::class,
            'PBES2-HS256+A128KW' => KeyEncryption\PBES2HS256A128KW::class,
            'PBES2-HS384+A192KW' => KeyEncryption\PBES2HS384A192KW::class,
            'PBES2-HS512+A256KW' => KeyEncryption\PBES2HS512A256KW::class,
            'RSA1_5' => KeyEncryption\RSA15::class,
            'RSA-OAEP' => KeyEncryption\RSAOAEP::class,
            'RSA-OAEP-256' => KeyEncryption\RSAOAEP256::class,
            'ES256' => SignatureAlgorithm\ES256::class,
            'ES384' => SignatureAlgorithm\ES384::class,
            'ES512' => SignatureAlgorithm\ES512::class,
            'EdDSA' => SignatureAlgorithm\EdDSA::class,
            'HS1' => SignatureAlgorithm\HS1::class,
            'HS256/64' => SignatureAlgorithm\HS256_64::class,
            'RS1' => SignatureAlgorithm\RS1::class,
            'HS256' => SignatureAlgorithm\HS256::class,
            'HS384' => SignatureAlgorithm\HS384::class,
            'HS512' => SignatureAlgorithm\HS512::class,
            'none' => SignatureAlgorithm\None::class,
            'PS256' => SignatureAlgorithm\PS256::class,
            'PS384' => SignatureAlgorithm\PS384::class,
            'PS512' => SignatureAlgorithm\PS512::class,
            'RS256' => SignatureAlgorithm\RS256::class,
            'RS384' => SignatureAlgorithm\RS384::class,
            'RS512' => SignatureAlgorithm\RS512::class,
        ];

        return $this->filterExistingClasses($algorithms);
    }

    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'jwt_module' => [
                'jku_factory' => [
                    'http_client' => ClientInterface::class,
                ],
                'algorithm_manager' => [
                    'algorithms' => $this->getAlgorithms(),
                ],
                'header_checker_manager' => [
                    'checkers' => [
                    ],
                    'token_types' => $this->filterExistingClasses([
                        JWSTokenSupport::class,
                        JWETokenSupport::class,
                    ]),
                ],
                'claim_checker_manager' => [
                    'checkers' => $this->filterExistingClasses([
                        'exp' => \Jose\Component\Checker\ExpirationTimeChecker::class,
                        'iat' => \Jose\Component\Checker\IssuedAtChecker::class,
                        'nbf' => \Jose\Component\Checker\NotBeforeChecker::class,
                    ]),
                ],
                'jws_serializer_manager' => [
                    'serializers' => $this->filterExistingClasses([
                        'jws_compact' => \Jose\Component\Signature\Serializer\CompactSerializer::class,
                        'jws_json_flattened' => \Jose\Component\Signature\Serializer\JSONFlattenedSerializer::class,
                        'jws_json_general' => \Jose\Component\Signature\Serializer\JSONGeneralSerializer::class,
                    ]),
                ],
                'jwe_serializer_manager' => [
                    'serializers' => $this->filterExistingClasses([
                        'jwe_compact' => \Jose\Component\Encryption\Serializer\CompactSerializer::class,
                        'jwe_json_flattened' => \Jose\Component\Encryption\Serializer\JSONFlattenedSerializer::class,
                        'jwe_json_general' => \Jose\Component\Encryption\Serializer\JSONGeneralSerializer::class,
                    ]),
                ],
                'compression_method_manager' => [
                    'compression_methods' => $this->filterExistingClasses([
                        'DEF' => \Jose\Component\Encryption\Compression\Deflate::class,
                    ]),
                ],

                'header_checker' => [],
                'claim_checker' => [],

                'jws_loader' => [],
                'jws_serializer' => [],
                'jws_builder' => [],
                'jws_verifier' => [],

                'jwe_loader' => [],
                'jwe_serializer' => [],
                'jwe_builder' => [],
                'jwe_decrypter' => [],
                'nested_token_builder' => [],
                'nested_token_loader' => [],

                'keys' => [],
                'key_sets' => [],
            ],
        ];
    }

    public function getDependencies(): array
    {
        return [
            'abstract_factories' => [
                DIFactory\Checker\HeaderCheckerManagerAbstractFactory::class,
                DIFactory\Checker\ClaimCheckerManagerAbstractFactory::class,
                DIFactory\KeyManagement\KeyAbstractFactory::class,
                DIFactory\KeyManagement\KeySetAbstractFactory::class,
                DIFactory\Signature\JWSBuilderAbstractFactory::class,
                DIFactory\Signature\JWSLoaderAbstractFactory::class,
                DIFactory\Signature\JWSSerializerAbstractFactory::class,
                DIFactory\Signature\JWSVerifierAbstractFactory::class,
                DIFactory\Encryption\JWEBuilderAbstractFactory::class,
                DIFactory\Encryption\JWEDecrypterAbstractFactory::class,
                DIFactory\Encryption\JWELoaderAbstractFactory::class,
                DIFactory\Encryption\JWESerializerAbstractFactory::class,
                DIFactory\NestedToken\NestedTokenLoaderAbstractFactory::class,
                DIFactory\NestedToken\NestedTokenBuilderAbstractFactory::class,
            ],
            'factories' => [
                AlgorithmManagerFactory::class => DIFactory\Core\AlgorithmManagerFactoryFactory::class,
                JWSSerializerManagerFactory::class => DIFactory\Signature\JWSSerializerManagerFactoryFactory::class,
                JWESerializerManagerFactory::class => DIFactory\Encryption\JWESerializerManagerFactoryFactory::class,
                CompressionMethodManagerFactory::class => DIFactory\Encryption\Compression\CompressionMethodManagerFactoryFactory::class,
                HeaderCheckerManagerFactory::class => DIFactory\Checker\HeaderCheckerManagerFactoryFactory::class,
                ClaimCheckerManagerFactory::class => DIFactory\Checker\ClaimCheckerManagerFactoryFactory::class,
                JWSBuilderFactory::class => DIFactory\Signature\JWSBuilderFactoryFactory::class,
                JWSVerifierFactory::class => DIFactory\Signature\JWSVerifierFactoryFactory::class,
                JWSLoaderFactory::class => DIFactory\Signature\JWSLoaderFactoryFactory::class,
                JWEBuilderFactory::class => DIFactory\Encryption\JWEBuilderFactoryFactory::class,
                JWEDecrypterFactory::class => DIFactory\Encryption\JWEDecrypterFactoryFactory::class,
                JWELoaderFactory::class => DIFactory\Encryption\JWELoaderFactoryFactory::class,
                NestedTokenBuilderFactory::class => DIFactory\NestedToken\NestedTokenBuilderFactoryFactory::class,
                NestedTokenLoaderFactory::class => DIFactory\NestedToken\NestedTokenLoaderFactoryFactory::class,
                JKUFactory::class => DIFactory\KeyManagement\JKUFactoryFactory::class,
                X5UFactory::class => DIFactory\KeyManagement\X5UFactoryFactory::class,
                // Serializer
                \Jose\Component\Signature\Serializer\CompactSerializer::class => InvokableFactory::class,
                \Jose\Component\Signature\Serializer\JSONFlattenedSerializer::class => InvokableFactory::class,
                \Jose\Component\Signature\Serializer\JSONGeneralSerializer::class => InvokableFactory::class,
                \Jose\Component\Encryption\Serializer\CompactSerializer::class => InvokableFactory::class,
                \Jose\Component\Encryption\Serializer\JSONFlattenedSerializer::class => InvokableFactory::class,
                \Jose\Component\Encryption\Serializer\JSONGeneralSerializer::class => InvokableFactory::class,
                // Compression
                \Jose\Component\Encryption\Compression\Deflate::class => InvokableFactory::class,
                // Claim Checker
                \Jose\Component\Checker\ExpirationTimeChecker::class => InvokableFactory::class,
                \Jose\Component\Checker\IssuedAtChecker::class => InvokableFactory::class,
                \Jose\Component\Checker\NotBeforeChecker::class => InvokableFactory::class,
                // Analyser
                \Jose\Component\KeyManagement\Analyzer\KeyAnalyzerManager::class => InvokableFactory::class,
                \Jose\Component\KeyManagement\Analyzer\AlgorithmAnalyzer::class => InvokableFactory::class,
                \Jose\Component\KeyManagement\Analyzer\UsageAnalyzer::class => InvokableFactory::class,
                \Jose\Component\KeyManagement\Analyzer\KeyIdentifierAnalyzer::class => InvokableFactory::class,
                \Jose\Component\KeyManagement\Analyzer\NoneAnalyzer::class => InvokableFactory::class,
                \Jose\Component\KeyManagement\Analyzer\OctAnalyzer::class => InvokableFactory::class,
                \Jose\Component\KeyManagement\Analyzer\RsaAnalyzer::class => InvokableFactory::class,
                // Token supports
                JWSTokenSupport::class => InvokableFactory::class,
                JWETokenSupport::class => InvokableFactory::class,
                // AESCBC
                ContentEncryption\A128CBCHS256::class => InvokableFactory::class,
                ContentEncryption\A192CBCHS384::class => InvokableFactory::class,
                ContentEncryption\A256CBCHS512::class => InvokableFactory::class,
                // AESGCM
                ContentEncryption\A128GCM::class => InvokableFactory::class,
                ContentEncryption\A192GCM::class => InvokableFactory::class,
                ContentEncryption\A256GCM::class => InvokableFactory::class,
                // AEDGCMKW
                KeyEncryption\A128GCMKW::class => InvokableFactory::class,
                KeyEncryption\A192GCMKW::class => InvokableFactory::class,
                KeyEncryption\A256GCMKW::class => InvokableFactory::class,
                // AEDGCMKW
                KeyEncryption\A128KW::class => InvokableFactory::class,
                KeyEncryption\A192KW::class => InvokableFactory::class,
                KeyEncryption\A256KW::class => InvokableFactory::class,
                // DIR
                KeyEncryption\Dir::class => InvokableFactory::class,
                // ECDH-ES
                KeyEncryption\ECDHES::class => InvokableFactory::class,
                KeyEncryption\ECDHESA128KW::class => InvokableFactory::class,
                KeyEncryption\ECDHESA192KW::class => InvokableFactory::class,
                KeyEncryption\ECDHESA256KW::class => InvokableFactory::class,
                // Experimental
                ContentEncryption\A128CCM_16_64::class => InvokableFactory::class,
                ContentEncryption\A128CCM_16_128::class => InvokableFactory::class,
                ContentEncryption\A128CCM_64_64::class => InvokableFactory::class,
                ContentEncryption\A128CCM_64_128::class => InvokableFactory::class,
                ContentEncryption\A256CCM_16_64::class => InvokableFactory::class,
                ContentEncryption\A256CCM_16_128::class => InvokableFactory::class,
                ContentEncryption\A256CCM_64_64::class => InvokableFactory::class,
                ContentEncryption\A256CCM_64_128::class => InvokableFactory::class,
                KeyEncryption\A128CTR::class => InvokableFactory::class,
                KeyEncryption\A192CTR::class => InvokableFactory::class,
                KeyEncryption\A256CTR::class => InvokableFactory::class,
                //KeyEncryption\Chacha20Poly1305::class => InvokableFactory::class,
                KeyEncryption\RSAOAEP384::class => InvokableFactory::class,
                KeyEncryption\RSAOAEP512::class => InvokableFactory::class,
                // PBES2
                KeyEncryption\PBES2HS256A128KW::class => InvokableFactory::class,
                KeyEncryption\PBES2HS384A192KW::class => InvokableFactory::class,
                KeyEncryption\PBES2HS512A256KW::class => InvokableFactory::class,
                // RSA
                KeyEncryption\RSA15::class => InvokableFactory::class,
                KeyEncryption\RSAOAEP::class => InvokableFactory::class,
                KeyEncryption\RSAOAEP256::class => InvokableFactory::class,
                // ECDSA
                SignatureAlgorithm\ES256::class => InvokableFactory::class,
                SignatureAlgorithm\ES384::class => InvokableFactory::class,
                SignatureAlgorithm\ES512::class => InvokableFactory::class,
                // EdDSA
                SignatureAlgorithm\EdDSA::class => InvokableFactory::class,
                // Experimental
                SignatureAlgorithm\HS1::class => InvokableFactory::class,
                SignatureAlgorithm\HS256_64::class => InvokableFactory::class,
                SignatureAlgorithm\RS1::class => InvokableFactory::class,
                // HMAC
                SignatureAlgorithm\HS256::class => InvokableFactory::class,
                SignatureAlgorithm\HS384::class => InvokableFactory::class,
                SignatureAlgorithm\HS512::class => InvokableFactory::class,
                // None
                SignatureAlgorithm\None::class => InvokableFactory::class,
                // RSA
                SignatureAlgorithm\PS256::class => InvokableFactory::class,
                SignatureAlgorithm\PS384::class => InvokableFactory::class,
                SignatureAlgorithm\PS512::class => InvokableFactory::class,
                SignatureAlgorithm\RS256::class => InvokableFactory::class,
                SignatureAlgorithm\RS384::class => InvokableFactory::class,
                SignatureAlgorithm\RS512::class => InvokableFactory::class,
            ],
        ];
    }
}
