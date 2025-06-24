<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Response;

class Psr15AdapterMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $psr17Factory = new Psr17Factory();

        $psrRequest = (new PsrHttpFactory(
            $psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory
        ))->createRequest($request);

        $psrResponse = app(MiddlewareInterface::class)
            ->process($psrRequest, new class($next, $request) implements RequestHandlerInterface {
            public function __construct(private readonly Closure $next, private readonly Request $laravelRequest) {}

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                $laravelResponse = ($this->next)($this->laravelRequest);

                return (new Psr17Factory())
                    ->createResponse($laravelResponse->getStatusCode())
                    ->withBody(Stream::create($laravelResponse->getContent()));
            }
        });

        return (new HttpFoundationFactory())->createResponse($psrResponse);
    }
}
