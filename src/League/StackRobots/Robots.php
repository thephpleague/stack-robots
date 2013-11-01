<?php

namespace League\StackRobots;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Robots implements HttpKernelInterface
{
    /**
     * @var \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    private $app;

    /**
     * @var string
     */
    private $env;

    /**
     * @var string
     */
    private $envVar;

    /**
     * Constructor
     *
     * @param HttpKernelInterface $app
     * @param string              $env    The environment variable to compare against.
     * @param string              $envVar The environment variable to inspect.
     */
    public function __construct(HttpKernelInterface $app, $env = 'production', $envVar = 'SERVER_ENV')
    {
        $this->app = $app;
        $this->env = $env;
        $this->envVar = $envVar;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        if (getenv($this->envVar) !== $this->env) {
            // @TODO: Add support for X-Robots-Tag

            if ($request->getPathInfo() === '/robots.txt') {
                return new Response("User-Agent: *\nDisallow: /", 200, array('Content-Type' => 'text/plain'));
            }
        }

        return $this->app->handle($request, $type, $catch);
    }
}
