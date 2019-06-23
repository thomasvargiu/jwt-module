<?php

declare(strict_types=1);

namespace TMV\JWTModule\Middleware;

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

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        JWKSet $jwkset,
        int $maxAge = 0
    ) {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->jwkset = $jwkset;
        $this->maxAge = $maxAge;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();

        if ($this->maxAge > 0) {
            $response = $response->withHeader('Cache-Control', 'max-age=' . $this->maxAge);
        }

        $jwks = \json_encode($this->jwkset->jsonSerialize());

        if (false === $jwks) {
            throw new RuntimeException('Unable to create jwk set json content');
        }

        return $response->withHeader('Content-Type', 'application/jwk-set+json; charset=UTF-8')
            ->withBody($this->streamFactory->createStream($jwks));
    }
}
