# JWS Serializers

## JWS Serializer Manager Factory Service

A JWSSerializerManagerFactory is available as a service in your 
application container:

```php
use Jose\Component\Signature\JWSSerializerManagerFactory;

$jwsSerializerManagerFactory = $container->get(JWSSerializerManagerFactory::class);
```

With this factory, you will be able to create the 
`JWSSerializerManager` you need:

```php
$jwsSerializerManager = $jwsSerializerManagerFactory->create(['jws_compact']);
```

You can now use the JWSSerializerManager as explained in the JWS 
Creation/Loading section.

Available JWS serialization modes are:
- `jws_compact`
- `jws_json_general`
- `jws_json_flattened`

## JWS Serializer Manager As Service

There is also another way to create a `JWSSerializerManager` object:
using the module configuration.

```php
return [
    'jwt_module' => [
        'jws_serializer' => [
            'serializer1' => [
                'serializers' => ['jws_compact'],
            ],
        ],
    ],
];
```

With the previous configuration, the bundle will create a public JWS 
Serializer Manager service named jwt_module.jws_serializer.serializer1 
with selected serialization modes.

```php
$jwsSerializerManager = $container->get('jwt_module.jws_serializer.serializer1');
```