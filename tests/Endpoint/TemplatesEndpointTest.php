<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Tests\Endpoint;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tiagoandrepro\WhatsAppCloud\Auth\StaticTokenProvider;
use Tiagoandrepro\WhatsAppCloud\Endpoint\TemplatesEndpoint;
use Tiagoandrepro\WhatsAppCloud\Serializer\JsonSerializer;
use Tiagoandrepro\WhatsAppCloud\Tests\Support\MockPsr18Client;
use Tiagoandrepro\WhatsAppCloud\Transport\Psr18Transport;
use Tiagoandrepro\WhatsAppCloud\Util\ErrorMapper;
use Tiagoandrepro\WhatsAppCloud\Util\RetryPolicy;
use Tiagoandrepro\WhatsAppCloud\Util\SafeLogger;

final class TemplatesEndpointTest extends TestCase
{
    public function testListAll(): void
    {
        $response = new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $factory = new Psr17Factory();

        $transport = new Psr18Transport(
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

        $endpoint = new TemplatesEndpoint($transport, '123');
        $endpoint->listAll('waba');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/waba/message_templates', (string) $request->getUri());
    }

    public function testDeleteByName(): void
    {
        $response = new Response(200, [], json_encode(['success' => true], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $factory = new Psr17Factory();

        $transport = new Psr18Transport(
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

        $endpoint = new TemplatesEndpoint($transport, '123');
        $result = $endpoint->deleteByName('waba', 'hello_world');

        self::assertTrue($result);
    }
}
