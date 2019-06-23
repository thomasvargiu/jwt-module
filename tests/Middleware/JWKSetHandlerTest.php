<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\Middleware;

use Jose\Component\Core\JWKSet;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use TMV\JWTModule\Middleware\JWKSetHandler;
use Zend\Diactoros\ResponseFactory;
use Zend\Diactoros\StreamFactory;

class JWKSetHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $responseFactory = new ResponseFactory();
        $streamFactory = new StreamFactory();
        $jwkset = $this->prophesize(JWKSet::class);

        $jwksData = ['keys' => []];

        $jwkset->jsonSerialize()->willReturn($jwksData);

        $handler = new JWKSetHandler(
            $responseFactory,
            $streamFactory,
            $jwkset->reveal(),
            102
        );

        $serverRequest = $this->prophesize(ServerRequestInterface::class);

        $response = $handler->handle($serverRequest->reveal());

        $this->assertSame('application/jwk-set+json; charset=UTF-8', $response->getHeader('content-type')[0] ?? null);
        $this->assertSame('max-age=102', $response->getHeader('cache-control')[0] ?? null);
        $this->assertSame('{"keys":[]}', $response->getBody()->getContents());
    }

    public function testHandleWithoutMaxAge(): void
    {
        $responseFactory = new ResponseFactory();
        $streamFactory = new StreamFactory();
        $jwkset = $this->prophesize(JWKSet::class);

        $jwksData = ['keys' => []];

        $jwkset->jsonSerialize()->willReturn($jwksData);

        $handler = new JWKSetHandler(
            $responseFactory,
            $streamFactory,
            $jwkset->reveal(),
            0
        );

        $serverRequest = $this->prophesize(ServerRequestInterface::class);

        $response = $handler->handle($serverRequest->reveal());

        $this->assertSame('application/jwk-set+json; charset=UTF-8', $response->getHeader('content-type')[0] ?? null);
        $this->assertCount(0, $response->getHeader('cache-control'));
        $this->assertSame('{"keys":[]}', $response->getBody()->getContents());
    }
}
