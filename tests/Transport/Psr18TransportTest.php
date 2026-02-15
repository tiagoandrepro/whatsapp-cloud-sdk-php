<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Tests\Transport;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tiagoandrepro\WhatsAppCloud\Auth\StaticTokenProvider;
use Tiagoandrepro\WhatsAppCloud\Serializer\JsonSerializer;
use Tiagoandrepro\WhatsAppCloud\Tests\Support\MockPsr18Client;
use Tiagoandrepro\WhatsAppCloud\Transport\Psr18Transport;
use Tiagoandrepro\WhatsAppCloud\Util\ErrorMapper;
use Tiagoandrepro\WhatsAppCloud\Util\RetryPolicy;
use Tiagoandrepro\WhatsAppCloud\Util\SafeLogger;

final class Psr18TransportTest extends TestCase
{
    public function testRetriesOnRateLimit(): void
    {
        $response429 = new Response(429, ['Retry-After' => '1'], json_encode(['error' => ['message' => 'rate limit']], JSON_THROW_ON_ERROR));
        $response200 = new Response(200, [], json_encode(['ok' => true], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response429, $response200]);
        $factory = new Psr17Factory();

        $transport = new Psr18Transport(
            $client,
            $factory,
            $factory,
            new StaticTokenProvider('token'),
            new JsonSerializer(),
            new ErrorMapper(),
            new SafeLogger(null),
            new RetryPolicy(1, 0, 0),
            'https://graph.facebook.com',
            'v24.0'
        );

        $payload = $transport->requestJson('GET', 'health');

        self::assertSame(['ok' => true], $payload);
        self::assertSame(2, $client->getRequestCount());
    }
}
