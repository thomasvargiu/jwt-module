# Nested Token Building

## Nested Token Builder Factory Service

A `NestedTokenBuilderFactory` is available as a service in your 
application container:

```php
use Jose\Component\NestedToken\NestedTokenBuilderFactory;

$nestedTokenBuilderFactory = $container->get(NestedTokenBuilderFactory::class);
```

With this factory, you will be able to create the 
`NestedTokenBuilder` you need:

```php
$nestedTokenBuilder = $nestedTokenBuilderFactory->create(
    $jweSerializers,
    $keyEncryptionAlgorithms, 
    $contentEncryptionAlgorithms,
    $compressionMethods,
    $jwsSerializers,
    $signatureAlgorithms
);
```

You can now use the NestedTokenBuilder as explained in the Nested Token
section of the documentation.

## Nested Token Builder As Service

There is also another way to create a `NestedTokenBuilder` object:
using the module configuration.

```php
return [
    'jwt_module' => [
        'nested_token_builder' => [
            'builder1' => [
                'jwe_serializers' => [
                    'jwe_compact',
                    'jwe_json_flattened',
                    'jwe_json_general',
                ],
                'key_encryption_algorithms' => ['A128CTR'],
                'content_encryption_algorithms' => ['A128GCM'],
                'compression_methods' => ['DEF'],
                'jws_serializers' => [
                    'jws_compact',
                    'jws_json_flattened',
                    'jws_json_general',
                ],
                'signature_algorithms' => ['RS512'],
            ],
        ],
    ],
];
```

With the previous configuration, the bundle will create a Nested Token 
Builder service named jwt_module.nested_token_builder.builder1 
with selected serialization modes.

```php
$nestedTokenBuilder = $container->get('jwt_module.nested_token_builder.builder1');
```
