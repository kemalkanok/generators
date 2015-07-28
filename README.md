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

Column fields when entering on terminal (default string):

```
name:string
surname
price:integer
publish:date
```
this generates migration like this :
```php
$table->string('name');
$table->string('surname');
$table->integer('price');
$table->date('publish');
```


