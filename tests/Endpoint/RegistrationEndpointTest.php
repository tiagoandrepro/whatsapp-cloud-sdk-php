<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Tests\Endpoint;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tiagoandrepro\WhatsAppCloud\Auth\StaticTokenProvider;
use Tiagoandrepro\WhatsAppCloud\Endpoint\RegistrationEndpoint;
use Tiagoandrepro\WhatsAppCloud\Serializer\JsonSerializer;
use Tiagoandrepro\WhatsAppCloud\Tests\Support\MockPsr18Client;
use Tiagoandrepro\WhatsAppCloud\Transport\Psr18Transport;
use Tiagoandrepro\WhatsAppCloud\Util\ErrorMapper;
use Tiagoandrepro\WhatsAppCloud\Util\RetryPolicy;
use Tiagoandrepro\WhatsAppCloud\Util\SafeLogger;

final class RegistrationEndpointTest extends TestCase
{
    public function testRegister(): void
    {
        $response = new Response(200, [], json_encode(['success' => true], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new RegistrationEndpoint($this->createTransport($client), '123');

        $endpoint->register('123456');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/123/register', (string) $request->getUri());

        $body = json_decode((string) $request->getBody(), true);
        self::assertSame('whatsapp', $body['messaging_product']);
        self::assertSame('123456', $body['pin']);
    }

    public function testDeregister(): void
    {
        $response = new Response(200, [], json_encode(['success' => true], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new RegistrationEndpoint($this->createTransport($client), '123');

        $endpoint->deregister();

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/123/deregister', (string) $request->getUri());
        self::assertSame('', (string) $request->getBody());
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
