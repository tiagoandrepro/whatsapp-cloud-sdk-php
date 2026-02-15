<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Tests\Endpoint;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tiagoandrepro\WhatsAppCloud\Auth\StaticTokenProvider;
use Tiagoandrepro\WhatsAppCloud\Endpoint\BusinessPortfolioEndpoint;
use Tiagoandrepro\WhatsAppCloud\Serializer\JsonSerializer;
use Tiagoandrepro\WhatsAppCloud\Tests\Support\MockPsr18Client;
use Tiagoandrepro\WhatsAppCloud\Transport\Psr18Transport;
use Tiagoandrepro\WhatsAppCloud\Util\ErrorMapper;
use Tiagoandrepro\WhatsAppCloud\Util\RetryPolicy;
use Tiagoandrepro\WhatsAppCloud\Util\SafeLogger;

final class BusinessPortfolioEndpointTest extends TestCase
{
    public function testGetBusinessPortfolio(): void
    {
        $response = new Response(200, [], json_encode(['id' => 'biz123', 'name' => 'My Business'], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new BusinessPortfolioEndpoint($this->createTransport($client), '123');

        $result = $endpoint->getBusinessPortfolio('biz123');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/biz123', (string) $request->getUri());
        self::assertSame('GET', $request->getMethod());
    }

    public function testGetBusinessPortfolioWithFields(): void
    {
        $response = new Response(200, [], json_encode(['id' => 'biz123'], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new BusinessPortfolioEndpoint($this->createTransport($client), '123');

        $endpoint->getBusinessPortfolio('biz123', ['id', 'name']);

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertStringContainsString('fields=id%2Cname', (string) $request->getUri());
    }

    private function createTransport(MockPsr18Client $client): Psr18Transport
    {
        $factory = new Psr17Factory();

        return new Psr18Transport(
            $client,
            $factory,
            $factory,
            new StaticTokenProvider('token'),
            new JsonSerializer(),
            new ErrorMapper(),
            new SafeLogger(null),
            new RetryPolicy(0, 0, 0),
            'https://graph.facebook.com',
            'v24.0'
        );
    }
}
