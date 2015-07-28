# laravel5 mvc generator

laravel5 mvc generator provides Resource management(CRUD)

# Installation

Edit composer.json file

```json
{
    "repositories": [
        {
          "type": "git",
          "url": "git@github.com:ismailakbudak/generators.git",
          "vendor-alias": "kanok/generators"
        }
    ],
    "require-dev": {
        "kanok/generators": "dev-develop", 
    },
    "autoload": {
        "psr-4": {
            "Kanok\\Generators\\": "vendor/kanok/generators/src/"
        }
    }
}
```

Add providers to config/app.php file

```php
return [
    'providers' => [
        Kanok\Generators\GeneratorsServiceProvider::class,
    ]
]
```

## Using

```
php artisan generate:mvc
```
