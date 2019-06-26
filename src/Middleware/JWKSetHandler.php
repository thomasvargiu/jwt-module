<?php

declare(strict_types=1);

namespace TMV\JWTModule\Middleware;

use Jose\Component\Core\JWK;
use Jose\Component\Core\JWKSet;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TMV\JWTModule\Exception\RuntimeException;

class JWKSetHandler implements RequestHandlerInterface
{
    /** @var ResponseFactoryInterface */
    private $responseFactory;

    /** @var StreamFactoryInterface */
    private $streamFactory;

    /** @var JWKSet */
    private $jwkset;

    /** @var int */
    private $maxAge;

    /** @var callable */
    private $jwkSetFactory;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        JWKSet $jwkset,
        int $maxAge = 0,
        callable $jwkSetFactory = null
    ) {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->jwkset = $jwkset;
        $this->maxAge = $maxAge;
        $this->jwkSetFactory = $jwkSetFactory ?: static function (array $keys) {
            return new JWKSet($keys);
        };
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();

        if ($this->maxAge > 0) {
            $response = $response->withHeader('Cache-Control', 'max-age=' . $this->maxAge);
        }

        $keys = \array_map(static function (JWK $jwk) {
            return $jwk->toPublic();
        }, $this->jwkset->all());

        $jwks = ($this->jwkSetFactory)($keys);

        if (! $jwks instanceof JWKSet) {
            throw new RuntimeException('Invalid JWKSet created');
        }

        $jwks = \json_encode($jwks->jsonSerialize());

        if (false === $jwks) {
            throw new RuntimeException('Unable to create jwk set json content');
        }

        return $response->withHeader('Content-Type', 'application/jwk-set+json; charset=UTF-8')
            ->withBody($this->streamFactory->createStream($jwks));
    }
}
