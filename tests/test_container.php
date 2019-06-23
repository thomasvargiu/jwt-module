<?php

use TMV\JWTModule\ConfigProvider;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\ServiceManager\ServiceManager;

require __DIR__ . '/../vendor/autoload.php';

$config = [
    'dependencies' => [
        'aliases' => [
            \Psr\Http\Client\ClientInterface::class => \Http\Mock\Client::class,
            \Psr\Http\Message\RequestFactoryInterface::class => \Zend\Diactoros\RequestFactory::class,
            \Psr\Http\Message\ResponseFactoryInterface::class => \Zend\Diactoros\ResponseFactory::class,
            \Psr\Http\Message\StreamFactoryInterface::class => \Zend\Diactoros\StreamFactory::class,
        ],
        'factories' => [
            \Http\Mock\Client::class => InvokableFactory::class,
            \Zend\Diactoros\RequestFactory::class => InvokableFactory::class,
            \Zend\Diactoros\ResponseFactory::class => InvokableFactory::class,
            \Zend\Diactoros\StreamFactory::class => InvokableFactory::class,
        ],
    ],
    'jwt_module' => [
        'header_checker' => [
            'checker1' => [
                'headers' => [],
            ],
        ],
        'claim_checker' => [
            'checker1' => [
                'claims' => [
                    'exp',
                    'iat',
                    'nbf',
                ],
            ],
        ],
        'jws_loader' => [
            'loader1' => [
                'serializers' => [
                    'jws_compact',
                    'jws_json_flattened',
                    'jws_json_general',
                ],
                'signature_algorithms' => [
                    'RS512',
                ],
                'header_checkers' => [],
            ],
        ],
        'jws_serializer' => [
            'serializer1' => [
                'serializers' => [
                    'jws_compact',
                    'jws_json_flattened',
                    'jws_json_general',
                ],
            ],
        ],
        'jws_builder' => [
            'builder1' => [
                'algorithms' => [
                    'RS512',
                ],
            ],
        ],
        'jws_verifier' => [
            'verifier1' => [
                'signature_algorithms' => [
                    'RS512',
                ],
            ],
        ],

        'jwe_loader' => [
            'loader1' => [
                'serializers' => [
                    'jwe_compact',
                    'jwe_json_flattened',
                    'jwe_json_general',
                ],
                'key_encryption_algorithms' => [
                    'A128CTR',
                ],
                'content_encryption_algorithms' => [
                    'A128GCM',
                ],
                'compression_methods' => [
                    'DEF',
                ],
                'header_checkers' => [],
            ],
        ],
        'jwe_serializer' => [
            'serializer1' => [
                'serializers' => [
                    'jwe_compact',
                    'jwe_json_flattened',
                    'jwe_json_general',
                ],
            ],
        ],
        'jwe_builder' => [
            'builder1' => [
                'key_encryption_algorithms' => [
                    'A128CTR',
                ],
                'content_encryption_algorithms' => [
                    'A128GCM',
                ],
                'compression_methods' => [
                    'DEF',
                ],
            ],
        ],
        'jwe_decrypter' => [
            'decrypter1' => [
                'key_encryption_algorithms' => [
                    'A128CTR',
                ],
                'content_encryption_algorithms' => [
                    'A128GCM',
                ],
                'compression_methods' => [
                    'DEF',
                ],
            ],
        ],
        'nested_token_builder' => [
            'builder1' => [
                'jwe_serializers' => [
                    'jwe_compact',
                    'jwe_json_flattened',
                    'jwe_json_general',
                ],
                'key_encryption_algorithms' => [
                    'A128CTR',
                ],
                'content_encryption_algorithms' => [
                    'A128GCM',
                ],
                'compression_methods' => [
                    'DEF',
                ],
                'jws_serializers' => [
                    'jws_compact',
                    'jws_json_flattened',
                    'jws_json_general',
                ],
                'signature_algorithms' => [
                    'RS512',
                ],
            ],
        ],
        'nested_token_loader' => [
            'loader1' => [
                'jwe_serializers' => [
                    'jwe_compact',
                    'jwe_json_flattened',
                    'jwe_json_general',
                ],
                'key_encryption_algorithms' => [
                    'A128CTR',
                ],
                'content_encryption_algorithms' => [
                    'A128GCM',
                ],
                'compression_methods' => [
                    'DEF',
                ],
                'jwe_header_checkers' => [],
                'jws_serializers' => [
                    'jws_compact',
                    'jws_json_flattened',
                    'jws_json_general',
                ],
                'signature_algorithms' => [
                    'RS512',
                ],
                'jws_header_checkers' => [],
            ],
        ],

        'keys' => [
            'key1' => [
                'type' => 'jwk',
                'options' => [
                    'value' => '{"foo":"bar","kty":"oct","k":"Zm9v"}',
                ],
            ],
        ],
        'key_sets' => [
            'set1' => [
                'type' => 'jwkset',
                'options' => [
                    'value' => '{"keys":[{"foo":"bar1","kty":"oct","k":"Zm9v"},{"foo":"bar2","kty":"oct","k":"Zm9v"}]}',
                ],
            ],
        ],
    ],
];

$configProvider = new ConfigProvider();
$config = \array_merge_recursive($configProvider(), $config);

$container = new ServiceManager($config['dependencies']);
$container->setService('config', $config);

return $container;
