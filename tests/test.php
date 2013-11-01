<?php

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
