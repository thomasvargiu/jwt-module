# Nested Token Loading

## Nested Token Loader Factory Service

A `NestedTokenLoaderFactory` is available as a service in your 
application container:

```php
use Jose\Component\NestedToken\NestedTokenLoaderFactory;

$nestedTokenLoaderFactory = $container->get(NestedTokenLoaderFactory::class);
```

With this factory, you will be able to create the 
`NestedTokenLoader` you need:

```php
$nestedTokenLoader = $nestedTokenLoaderFactory->create(
    $jweSerializers,
    $keyEncryptionAlgorithms,
    $contentEncryptionAlgorithms,
    $compressionMethods,
    $jweHeaderCheckers
);
```

You can now use the NestedTokenLoader as explained in the Nested Token
section of the documentation.

## Nested token Loader As Service

There is also another way to create a `NestedTokenLoader` object:
using the module configuration.

```php
return [
    'jwt_module' => [
        'nested_token_loader' => [
            'loader1' => [
                'jwe_serializers' => [
                    'jwe_compact',
                    'jwe_json_flattened',
                    'jwe_json_general',
                ],
                'key_encryption_algorithms' => ['A128CTR'],
                'content_encryption_algorithms' => ['A128GCM'],
                'compression_methods' => ['DEF'],
                'jwe_header_checkers' => [],
                'jws_serializers' => [
                    'jws_compact',
                    'jws_json_flattened',
                    'jws_json_general',
                ],
                'signature_algorithms' => ['RS512'],
                'jws_header_checkers' => [],
            ],
        ],
    ],
];
```

With the previous configuration, the bundle will create a Nested Token 
Loader service named jwt_module.nested_token_loader.loader1 
with selected serialization modes.

```php
$nestedTokenLoader = $container->get('jwt_module.nested_token_loader.loader1');
```
