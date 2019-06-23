# Algorithm Management

## Algorithm Manager Factory Service

The Zend Module provides an Algorithm Manager Factory service.
The available algorithms depends on the components installed on
your application.

```php
use Jose\Component\Core\AlgorithmManagerFactory;

$algorithmManagerFactory = $container->get(AlgorithmManagerFactory::class);
$algorithmManager = $algorithmManagerFactory->create(['RS256', 'HS512']);
```

## Adding algorithms

All algorithms provided by the JWT Framework are already registered
with invokable factories.

```php
return [
    'jwt_module' => [
        'algorithm_manager' => [
            'algorithms' => [
                // key-value alias-service name
                'RS256' => Jose\Component\Signature\Algorithm\RS256::class,
                // Use the default name provided by the Algorithm class
                Jose\Component\Signature\Algorithm\RS384::class,
                // Custom algorithm
                'CustomAlgorithm' => My\Module\Algorithm\CustomAlgorithm::class,
            ],
        ],
    ],
];
```

> **Note:** If you need to add a custom algorithm you need to register 
> it in your service container first.

## PBES2-* Algorithms

PBES2-* algorithms are registered in the service container with their
default configuration, i.e. salt size = 62 bits and count = 4096.
If these values does not fit on your needs, you can create a new 
algorithm service with its own factory, initializing it with your
own values.