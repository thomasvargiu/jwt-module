# Key Set Management (JWKSet)

## Key Sets As Services

The key set configuration will look like as follow:

```php
return [
    'jwt_modules' => [ // Configuration of the JWT Framework
        'key_sets' => [ // Configuration of the key sets
            'keyset_name' => [ // Unique key name
                'type' => 'jwkset', // Name of the method
                'options' => [ // Factory options
                    // ...
                ],
            ],
        ],
    ],
];
```

The key set will be available as a container service with the name
`jwt_module.key_sets.keyset_name` where `keyset_name` is the unique name
of your key set.
Each key set service will be an instance of the
`Jose\Component\Core\JWKSet` class.


### From A JWKSet Object

This method will directly get a JWKSet object.

```php
return [
    'jwt_modules' => [
        'key_sets' => [
            'keyset_name' => [
                'type' => 'jwkset',
                'options' => [
                    'value' => '{"keys":[{"foo":"bar1","kty":"oct","k":"Zm9v"},{"foo":"bar2","kty":"oct","k":"Zm9v"}]}',
                ],
            ],
        ],
    ],
];
```

### Distant Key Sets

You can load key sets shared by a distant service
(e.g. Google, Microsoft, Okta...).

To use it you should have an `psr/http-client` compatible client
and `psr/http-factory` compatible factories registered.

By default, a `Psr\Http\Client\ClientInterface` service name is used
as HTTP client service. If you need to use another service that
implements `Psr\Http\Client\ClientInterface` interface you can do it
with the following configuration:

```php
return [
    'jwt_module' => [
        'jku_factory' => [
            'http_client' => 'my_http_client',
        ],
    ],
];
```

> **Important recommendations:**
> 
> - It is **highly recommended** to use a cache plugin for your HTTP
>   client and thus avoid unnecessary calls to the key set endpoint.
> - The connection **must be secured** and certificate verification
>   should not be disabled.

#### From A JKU (JWK Url)

The following example will allow you tu load a key set from a distant
URI. The key set must be a JWKSet object.

```php
return [
    'jwt_modules' => [
        'key_sets' => [
            'keyset_name' => [
                'type' => 'jwku',
                'options' => [
                    'url' => 'https://login.microsoftonline.com/common/discovery/keys',
                    'headers' => [
                        // headers
                        'authorization' => 'custom-auth'
                    ],
                ],
            ],
        ],
    ],
];
```

#### From A X5U (X509 Certificates Url)

The following example will allow you tu load a key set from a distant
URI. The key set must be a list of X509 certificates.

```php
return [
    'jwt_module' => [
        'key_sets' => [
            'keyset_name' => [
                'type' => 'x5u',
                'options' => [
                    'url' => 'https://www.googleapis.com/oauth2/v1/certs',
                    'headers' => [
                        // headers
                        'authorization' => 'custom-auth'
                    ],
                ],
            ],
        ],
    ],
];
```

## Shared Ket Sets

It can be interesting to share your key sets through an Url.
A `psr/http-server-handler` compatible handler is available.

```php
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use TMV\JWTModule\Middleware\JWKSetHandler;

$handler = JWKSetHandler(
    $container->get(ResponseFactoryInterface::class),
    $container->get(StreamFactoryInterface::class),
    $container->get('jwt_module.key_sets.keyset_name'),
    3600 // optional http cache max-age, a "0" value will disable it
);
```
