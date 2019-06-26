<?php

declare(strict_types=1);

namespace TMV\JWTModuleTest\Middleware;

use Jose\Component\Core\JWK;
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
        $jwk1 = $this->prophesize(JWK::class);
        $jwk2 = $this->prophesize(JWK::class);
        $jwkR1 = $this->prophesize(JWK::class);
        $jwkR2 = $this->prophesize(JWK::class);

        $jwkSet = $this->prophesize(JWKSet::class);
        $jwkSet->all()->willReturn([$jwk1->reveal(), $jwk2->reveal()]);

        $jwk1->toPublic()->shouldBeCalled()->willReturn($jwkR1->reveal());

        $jwk2->toPublic()->shouldBeCalled()->willReturn($jwkR2->reveal());

        $jwkSetResult = $this->prophesize(JWKSet::class);
        $jwkSetResult->jsonSerialize()->shouldBeCalled()->willReturn([
            'keys' => [
                ['foo' => 'bar'],
            ],
        ]);

        $jwkSetFactory = function (array $keys) use ($jwkR1, $jwkR2, $jwkSetResult) {
            $this->assertSame([$jwkR1->reveal(), $jwkR2->reveal()], $keys);

            return $jwkSetResult->reveal();
        };

        $handler = new JWKSetHandler(
            $responseFactory,
            $streamFactory,
            $jwkSet->reveal(),
            102,
            $jwkSetFactory
        );

        $serverRequest = $this->prophesize(ServerRequestInterface::class);

        $response = $handler->handle($serverRequest->reveal());

        $this->assertSame('application/jwk-set+json; charset=UTF-8', $response->getHeader('content-type')[0] ?? null);
        $this->assertSame('max-age=102', $response->getHeader('cache-control')[0] ?? null);
        $this->assertSame('{"keys":[{"foo":"bar"}]}', $response->getBody()->getContents());
    }

    public function testHandleWithoutMaxAge(): void
    {
        $responseFactory = new ResponseFactory();
        $streamFactory = new StreamFactory();
        $jwk1 = $this->prophesize(JWK::class);
        $jwk2 = $this->prophesize(JWK::class);
        $jwkR1 = $this->prophesize(JWK::class);
        $jwkR2 = $this->prophesize(JWK::class);

        $jwkSet = $this->prophesize(JWKSet::class);
        $jwkSet->all()->willReturn([$jwk1->reveal(), $jwk2->reveal()]);

        $jwk1->toPublic()->shouldBeCalled()->willReturn($jwkR1->reveal());

        $jwk2->toPublic()->shouldBeCalled()->willReturn($jwkR2->reveal());

        $jwkSetResult = $this->prophesize(JWKSet::class);
        $jwkSetResult->jsonSerialize()->shouldBeCalled()->willReturn([
            'keys' => [
                ['foo' => 'bar'],
            ],
        ]);

        $jwkSetFactory = function (array $keys) use ($jwkR1, $jwkR2, $jwkSetResult) {
            $this->assertSame([$jwkR1->reveal(), $jwkR2->reveal()], $keys);

            return $jwkSetResult->reveal();
        };

        $handler = new JWKSetHandler(
            $responseFactory,
            $streamFactory,
            $jwkSet->reveal(),
            0,
            $jwkSetFactory
        );

        $serverRequest = $this->prophesize(ServerRequestInterface::class);

        $response = $handler->handle($serverRequest->reveal());

        $this->assertCount(0, $response->getHeader('cache-control'));
    }
}
