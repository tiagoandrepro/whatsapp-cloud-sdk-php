<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Tests\Endpoint;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tiagoandrepro\WhatsAppCloud\Auth\StaticTokenProvider;
use Tiagoandrepro\WhatsAppCloud\Endpoint\WebhookSubscriptionsEndpoint;
use Tiagoandrepro\WhatsAppCloud\Serializer\JsonSerializer;
use Tiagoandrepro\WhatsAppCloud\Tests\Support\MockPsr18Client;
use Tiagoandrepro\WhatsAppCloud\Transport\Psr18Transport;
use Tiagoandrepro\WhatsAppCloud\Util\ErrorMapper;
use Tiagoandrepro\WhatsAppCloud\Util\RetryPolicy;
use Tiagoandrepro\WhatsAppCloud\Util\SafeLogger;

final class WebhookSubscriptionsEndpointTest extends TestCase
{
    public function testOverrideCallback(): void
    {
        $response = new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new WebhookSubscriptionsEndpoint($this->createTransport($client), '123');

        $endpoint->overrideCallback('waba', 'https://example.com/callback', 'token');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/waba/subscribed_apps', (string) $request->getUri());

        $body = json_decode((string) $request->getBody(), true);
        self::assertSame('https://example.com/callback', $body['override_callback_uri']);
        self::assertSame('token', $body['verify_token']);
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
