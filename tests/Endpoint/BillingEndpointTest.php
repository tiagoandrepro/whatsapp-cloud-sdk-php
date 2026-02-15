<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Tests\Endpoint;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tiagoandrepro\WhatsAppCloud\Auth\StaticTokenProvider;
use Tiagoandrepro\WhatsAppCloud\Endpoint\BillingEndpoint;
use Tiagoandrepro\WhatsAppCloud\Serializer\JsonSerializer;
use Tiagoandrepro\WhatsAppCloud\Tests\Support\MockPsr18Client;
use Tiagoandrepro\WhatsAppCloud\Transport\Psr18Transport;
use Tiagoandrepro\WhatsAppCloud\Util\ErrorMapper;
use Tiagoandrepro\WhatsAppCloud\Util\RetryPolicy;
use Tiagoandrepro\WhatsAppCloud\Util\SafeLogger;

final class BillingEndpointTest extends TestCase
{
    public function testGetCreditLines(): void
    {
        $response = new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new BillingEndpoint($this->createTransport($client), '123');

        $result = $endpoint->getCreditLines('biz123');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/biz123/extendedcredits', (string) $request->getUri());
        self::assertSame('GET', $request->getMethod());
    }

    public function testGetCreditLinesWithFields(): void
    {
        $response = new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new BillingEndpoint($this->createTransport($client), '123');

        $endpoint->getCreditLines('biz123', ['id', 'balance']);

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertStringContainsString('fields=id%2Cbalance', (string) $request->getUri());
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
