<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Tests\Endpoint;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tiagoandrepro\WhatsAppCloud\Auth\StaticTokenProvider;
use Tiagoandrepro\WhatsAppCloud\Endpoint\BlockUsersEndpoint;
use Tiagoandrepro\WhatsAppCloud\Serializer\JsonSerializer;
use Tiagoandrepro\WhatsAppCloud\Tests\Support\MockPsr18Client;
use Tiagoandrepro\WhatsAppCloud\Transport\Psr18Transport;
use Tiagoandrepro\WhatsAppCloud\Util\ErrorMapper;
use Tiagoandrepro\WhatsAppCloud\Util\RetryPolicy;
use Tiagoandrepro\WhatsAppCloud\Util\SafeLogger;

final class BlockUsersEndpointTest extends TestCase
{
    public function testGetBlockedUsers(): void
    {
        $response = new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new BlockUsersEndpoint($this->createTransport($client), '123');

        $result = $endpoint->getBlockedUsers();

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/123/block_users', (string) $request->getUri());
        self::assertSame('GET', $request->getMethod());
    }

    public function testBlockUsers(): void
    {
        $response = new Response(200, [], json_encode(['success' => true], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new BlockUsersEndpoint($this->createTransport($client), '123');

        $endpoint->blockUsers(['+15551234567', '+15559876543']);

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/123/block_users', (string) $request->getUri());
        self::assertSame('POST', $request->getMethod());

        $body = json_decode((string) $request->getBody(), true);
        self::assertSame('whatsapp', $body['messaging_product']);
        self::assertCount(2, $body['block_users']);
    }

    public function testUnblockUsers(): void
    {
        $response = new Response(200, [], json_encode(['success' => true], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new BlockUsersEndpoint($this->createTransport($client), '123');

        $endpoint->unblockUsers(['+15551234567']);

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/123/block_users', (string) $request->getUri());
        self::assertSame('DELETE', $request->getMethod());
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
