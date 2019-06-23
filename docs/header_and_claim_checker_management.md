# Header and Claim Checker Management

## Checker Manager Factory Services

The component provides Header and Claim Checker Manager Factory services.
These services are available when the `web-token/jwt-checker` component
is installed:

```
composer require web-token/jwt-checker
```

```php
use Jose\Component\Checker\HeaderCheckerManagerFactory;
use Jose\Component\Checker\ClaimCheckerManagerFactory;

$headerCheckerManagerFactory = $container->get(HeaderCheckerManagerFactory::class);
$headerCheckerManager = $headerCheckerManagerFactory->create([...]);

$claimCheckerManagerFactory = $container->get(ClaimCheckerManagerFactory::class);
$claimCheckerManager = $claimCheckerManagerFactory->create([...]);
```

## Checker Manager Services

You can create Header and Claim Checker Managers using the module
configuration.

```php
return [
    'jwt_module' => [
        'claim_checker' => [
            'checker1' => [
                'claims' => [
                    'foo',
                ],
            ],
        ],
        'header_checker' => [
            'checker1' => [
                'headers' => [
                    'foo',
                ],
            ],
        ],
    ],
];
```

With the previous configuration, the component will create Header and 
Claim Checker Managers named `jwt_module.header_checker.checker1` and 
`jwt_module.claim_checker.checker1` with selected checkers.
