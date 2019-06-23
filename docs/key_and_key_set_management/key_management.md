# Key Management (JWK)

## Keys As Services

When the component is installed, you will be able to define your keys 
in your application configuration and load your keys from several 
sources or formats.

### From a shared secret

This method will directly get a shared secret.

```php
return [
    'jwt_modules' => [
        'keys' => [
            'key1' => [
                'type' => 'secret',
                'options' => [
                    'additional_values' => [
                        'use' => 'sig',
                        'alg' => 'RS512',
                    ],
                ],
            ],
        ],
    ],
];
```

```php
$jwk = $container->get('jwt_module.keys.key1');
```

### From a JWK Object

```php
return [
    'jwt_modules' => [
        'keys' => [
            'key1' => [
                'type' => 'jwk',
                'options' => [
                    'value' => '{"foo":"bar","kty":"oct","k":"Zm9v"}',
                ],
            ],
        ],
    ],
];
```

### From A X509 Certificate File

```php
return [
    'jwt_modules' => [
        'keys' => [
            'key1' => [
                'type' => 'certificate',
                'options' => [
                    'path' => '/path/to/your/X509/certificate',
                    'additional_values' => [
                        'use' => 'sig',
                        'alg' => 'RS512',
                    ],
                ],
            ],
        ],
    ],
];
```

### From A X509 Certificate

```php
return [
    'jwt_modules' => [
        'keys' => [
            'key1' => [
                'type' => 'x5c',
                'options' => [
                    'value' => '-----BEGIN CERTIFICATE----- ....',
                    'additional_values' => [
                        'use' => 'sig',
                        'alg' => 'RS512',
                    ],
                ],
            ],
        ],
    ],
];
```

### From A PKCS#1/PKCS#8 Key File

```php
return [
    'jwt_modules' => [
        'keys' => [
            'key1' => [
                'type' => 'file',
                'options' => [
                    'path' => '/path/to/your/key/file',
                    'password' => 'secret',
                    'additional_values' => [
                        'use' => 'sig',
                        'alg' => 'RS512',
                    ],
                ],
            ],
        ],
    ],
];
```

### From A Key In A Key Set

```php
return [
    'jwt_modules' => [
        'keys' => [
            'key1' => [
                'type' => 'jwkset',
                'options' => [
                    'key_set' => 'jwt_module_key_sets.set1', // JWKSet service
                    'index' => 0, // Use key at index 0
                ],
            ],
        ],
    ],
];
```
