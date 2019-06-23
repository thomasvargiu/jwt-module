# JWE decryption

## JWE Decrypter Factory Service

A `JWEDecrypterFactory` is available as a service in your application 
container:

```php
use Jose\Component\Encryption\JWEDecrypterFactory;

$jweDecrypterFactory = $container->get(JWEDecrypterFactory::class);
```

With this factory, you will be able to create the JWEDecrypter you need:

```php
$jweDecrypter = $jweDecrypterFactory->create(['HS256']);
```

You can now use the JWEDecrypter as explained in the JWE Creation 
section.

> **Reminder:**
> 
> It is important to check the token headers. See the checker section 
> of the documentation.

## JWE Decrypter As Service

There is also another way to create a JWEDecrypter object: using the 
module configuration.

```php
return [
    'jwt_module' => [
        'jwe_decrypter' => [
            'decrypter1' => [
                'key_encryption_algorithms' => ['A256GCMKW'],
                'content_encryption_algorithms' => ['A256CBC-HS256'],
                'compression_methods' => ['DEF'],
            ],
        ],
    ],
];
```

With the previous configuration, the module will create a JWE Decrypter 
service named jwt_module.jwe_decrypter.decrypter1 with selected 
encryption algorithms..

```php
$jweDecrypter = $container->get('jwt_module.jwe_decrypter.decrypter1');
```
