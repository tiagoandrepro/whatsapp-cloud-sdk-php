<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Tests\Endpoint;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tiagoandrepro\WhatsAppCloud\Auth\StaticTokenProvider;
use Tiagoandrepro\WhatsAppCloud\Endpoint\AnalyticsEndpoint;
use Tiagoandrepro\WhatsAppCloud\Serializer\JsonSerializer;
use Tiagoandrepro\WhatsAppCloud\Tests\Support\MockPsr18Client;
use Tiagoandrepro\WhatsAppCloud\Transport\Psr18Transport;
use Tiagoandrepro\WhatsAppCloud\Util\ErrorMapper;
use Tiagoandrepro\WhatsAppCloud\Util\RetryPolicy;
use Tiagoandrepro\WhatsAppCloud\Util\SafeLogger;

final class AnalyticsEndpointTest extends TestCase
{
    public function testGetAnalytics(): void
    {
        $response = new Response(200, [], json_encode(['analytics' => []], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new AnalyticsEndpoint($this->createTransport($client), '123');

        $fields = 'analytics.start(1).end(2).granularity(DAY).phone_numbers([]).country_codes(["US","BR"])';
        $endpoint->getAnalytics('waba', $fields);

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame(
            'https://graph.facebook.com/v24.0/waba?fields=analytics.start%281%29.end%282%29.granularity%28DAY%29.phone_numbers%28%5B%5D%29.country_codes%28%5B%22US%22%2C%22BR%22%5D%29',
            (string) $request->getUri()
        );
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
