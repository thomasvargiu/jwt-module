# JWS Verification

## JWS Verifier Factory Service

A `JWSVerifierFactory` is available as a service in your application
container:

```php
use Jose\Component\Signature\JWSVerifierFactory;

$jwsVerifierFactory = $container->get(JWSVerifierFactory::class);
```

With this factory, you will be able to create the JWSVerifier you need:

```php
$jwsVerifier = $jwsVerifierFactory->create(['HS256']);
```

You can now use the JWSVerifier as explained in the JWS Creation section.

> **Reminder:**
> 
> Reminder: it is important to check the token headers.
> See the checker section of this documentation.

## JWS Verifier As Service

There is also another way to create a JWSVerifier object: using the 
module configuration.

```php
return [
    'jwt_module' => [
        'jws_verifier' => [
            'verifier1' => [
                'signature_algorithms' => ['HS256', 'RS256', 'ES256'],
            ],
        ],
    ],
];
```

With the previous configuration, the module will create a public JWS 
Verifier service named jwt_module.jws_verifier.verifier1 with selected 
signature algorithms.

```php
$jwsVerifier = $container->get('jwt_module.jws_verifier.verifier1');
```
