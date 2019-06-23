# JWS creation

## JWS Builder Factory Service

A `JWSBuilderFactory` is available as a service in your application
container:

```php
use Jose\Component\Signature\JWSBuilderFactory;

$jwsBuilderFactory = $container->get(JWSBuilderFactory::class);
```

With this factory, you will be able to create the JWSBuilder you need:

```php
$jwsBuilder = $jwsBuilderFactory->create(['HS256']);
```

You can now use the JWSBuilder as explained in the JWS Creation section.


## JWS Builder As Service

There is also another way to create a JWSBuilder object: using the 
module configuration.

```php
return [
    'jwt_module' => [
        'jws_builder' => [
            'builder1' => [
                'algorithms' => ['HS256'],
            ],
        ],
    ],
];
```

With the previous configuration, the module will create a public JWS 
Builder service named jwt_module.jws_builder.builder1 with selected 
signature algorithms.

```php
$jwsBuilder = $container->get('jwt_module.jws_builder.builder1');
```
