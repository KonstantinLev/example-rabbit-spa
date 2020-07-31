<?php

declare(strict_types=1);

namespace Api\Test\Feature;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;

class WebTestCase extends TestCase
{
    protected function get(string $uri): ResponseInterface
    {
        return $this->method($uri, 'GET');
    }

    /**
     * @param string $uri
     * @param $method
     * @return ResponseInterface
     * @throws \Throwable
     */
    protected function method(string $uri, $method): ResponseInterface
    {
        return $this->request(
            (new ServerRequest())
                ->withUri(new Uri('http://test' . $uri))
                ->withMethod($method)
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Throwable
     */
    protected function request(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->app()->process($request, new Response());
        $response->getBody()->rewind();
        return $response;
    }

    private function app(): App
    {
        $container = $this->container();
        $app = new App($container);
        (require 'config/routes.php')($app);
        return $app;
    }

    private function container(): ContainerInterface
    {
        return require 'config/container.php';
    }
}