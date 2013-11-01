# League\StackRobots

StackRobots is a middleware for [StackPHP](http://stackphp.com), based heavily off of [Cylon](https://github.com/dmathieu/cylon) for Ruby.
It provides a default robots.txt for non-production environments.

## Install Via Composer

```json
{
    "require": {
        "league/stack-robots": "~1.0@dev"
    }
}
```

## Example

```php
include_once '../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use League\StackRobots\Robots;

$app = new Stack\CallableHttpKernel(function (Request $request) {
    return new Response('Hello World!');
});

putenv('SERVER_ENV=dev');

$app = (new Stack\Builder)
    ->push('League\\StackRobots\\Robots', 'production')
    ->resolve($app);

Stack\run($app);
```
