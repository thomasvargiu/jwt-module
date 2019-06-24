# JWE serializers

## JWE Serializer Manager Factory Service

A `JWESerializerManagerFactory` is available as a service in your
application container:

```php
use Jose\Component\Encryption\JWESerializerManagerFactory;

$jweSerializerManagerFactory = $container->get(JWESerializerManagerFactory::class);
```

With this factory, you will be able to create the JWESerializerManager 
you need:

```php
$jweSerializerManager = $jweSerializerManagerFactory->create(['jwe_compact']);
```

You can now use the JWESerializerManager as explained in the JWE 
Creation/Loading section.


Available JWE serialization modes are:
- `jwe_compact`
- `jwe_json_general`
- `jwe_json_flattened`


## JWE Serializer Manager As Service

There is also another way to create a JWESerializerManager object: 
using the module configuration.

```php
return [
    'jwt_module' => [
        'jwe_serializer' => [
            'serializer1' => [
                'serializers' => ['jwe_compact'],
            ],
        ],
    ],
];
```

With the previous configuration, the bundle will create a public JWE 
Serializer Manager service named jwt_module.jwe_serializer.serializer1 
with selected serialization modes.

```php
$jweSerializerManager = $container->get('jose.jwe_serializer.serializer1');
```
