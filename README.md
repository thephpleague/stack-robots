# League\StackRobots

[![Build Status](https://travis-ci.org/php-loep/StackRobots.png?branch=master)](https://travis-ci.org/php-loep/StackRobots)
[![Total Downloads](https://poser.pugx.org/league/stack-robots/downloads.png)](https://packagist.org/packages/league/stack-robots)
[![Latest Stable Version](https://poser.pugx.org/league/stack-robots/v/stable.png)](https://packagist.org/packages/league/stack-robots)

StackRobots is a middleware for [StackPHP](http://stackphp.com). It provides a default robots.txt for
non-production environments.

## Install Via Composer

```json
{
    "require": {
        "league/stack-robots": "~1.0"
    }
}
```

## Usage

StackRobots is a very simple middleware. By default it looks at the `SERVER_ENV` environment variable,
and if the `SERVER_ENV` does not equal production, it captures the response and sets an `X-Robots-Tag`
header with a value of `noindex, nofollow, noarchive`.

When you push the middleware on to the stack, you can pass 2 additional parameters, `$env` and `$envVar`.
The `$env` parameter is the environment in which you want this middleware to not do anything, typically
`production`. The `$envVar` parameter is the environment variable that holds the environment of the
current server; it defaults to `SERVER_ENV`.

If the value of `SERVER_ENV` matches the value that is passed, this middleware will just pass control on
to the next middleware. However, if it does not match, then StackRobots will set the `X-Robots-Tag`.
Additionally, if the incoming request is for your `/robots.txt` file, then StackRobots will stop the request
and send the following response.

```php
return new Response("User-Agent: *\nDisallow: /", 200, array('Content-Type' => 'text/plain'));
```
And this is what the browser receives.
```txt
User-Agent: *
Disallow: /
```

> More info on the `X-Robots-Tag` is available [here](https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag).

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
    ->push('League\\StackRobots\\Robots')
    ->resolve($app);

Stack\run($app);
```

## Authors

- Don Gilbert [@dilbert4life](http://twitter.com/dilbert4life)
- Inspired by [Cylon](https://github.com/dmathieu/cylon) for Ruby.