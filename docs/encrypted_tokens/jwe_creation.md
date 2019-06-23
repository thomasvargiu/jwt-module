# JWE creation

## JWE Builder Factory Service

A `JWEBuilderFactory` is available as a service in your application
container:

```php
use Jose\Component\Encryption\JWEBuilderFactory;

$jweBuilderFactory = $container->get(JWEBuilderFactory::class);
```

With this factory, you will be able to create the JWEBuilder you need:

```php
$jweBuilder = $jweBuilderFactory->create(
    ['A256GCMKW'],
    ['A256CBC-HS256'],
    ['DEF'] // Compression methods
);
```

Available compression methods are:
- `DEF`: deflate (recommended)

You can now use the JWEBuilder as explained in the JWE Creation section.


## JWE Builder As Service

There is also another way to create a JWEBuilder object: using the 
module configuration.

```php
return [
    'jwt_module' => [
        'jwe_builder' => [
            'builder1' => [
                'key_encryption_algorithms' => ['A256GCMKW'],
                'content_encryption_algorithms' => ['A256CBC-HS256'],
                'compression_methods' => ['DEF'],
            ],
        ],
    ],
];
```

With the previous configuration, the module will create a public JWS 
Builder service named jwt_module.jwe_builder.builder1 with selected 
encryption algorithms..

```php
$jweBuilder = $container->get('jwt_module.jwe_builder.builder1');
```
