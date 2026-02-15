<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Tests\Endpoint;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tiagoandrepro\WhatsAppCloud\Auth\StaticTokenProvider;
use Tiagoandrepro\WhatsAppCloud\Endpoint\FlowsEndpoint;
use Tiagoandrepro\WhatsAppCloud\Serializer\JsonSerializer;
use Tiagoandrepro\WhatsAppCloud\Tests\Support\MockPsr18Client;
use Tiagoandrepro\WhatsAppCloud\Transport\Psr18Transport;
use Tiagoandrepro\WhatsAppCloud\Util\ErrorMapper;
use Tiagoandrepro\WhatsAppCloud\Util\RetryPolicy;
use Tiagoandrepro\WhatsAppCloud\Util\SafeLogger;

final class FlowsEndpointTest extends TestCase
{
    public function testCreateFlow(): void
    {
        $response = new Response(200, [], json_encode(['id' => 'flow123'], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new FlowsEndpoint($this->createTransport($client), '123');

        $flow = $endpoint->createFlow('waba123', 'Order Flow', ['BOOKING', 'SALES']);

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/waba123/flows', (string) $request->getUri());
        self::assertSame('POST', $request->getMethod());
    }

    public function testMigrateFlows(): void
    {
        $response = new Response(200, [], json_encode(['migration_id' => 'mig123'], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new FlowsEndpoint($this->createTransport($client), '123');

        $endpoint->migrateFlows('waba_dest', 'waba_src');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/waba_dest/migrate_flows', (string) $request->getUri());
        self::assertSame('POST', $request->getMethod());
    }

    public function testGetFlow(): void
    {
        $response = new Response(200, [], json_encode(['id' => 'flow123'], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new FlowsEndpoint($this->createTransport($client), '123');

        $endpoint->getFlow('flow123');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/flow123', (string) $request->getUri());
        self::assertSame('GET', $request->getMethod());
    }

    public function testGetPreviewUrl(): void
    {
        $response = new Response(200, [], json_encode(['preview_url' => 'http://preview.example.com'], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new FlowsEndpoint($this->createTransport($client), '123');

        $endpoint->getPreviewUrl('flow123', true);

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertStringContainsString('preview.invalidate', (string) $request->getUri());
        self::assertStringContainsString('true', (string) $request->getUri());
    }

    public function testListFlows(): void
    {
        $response = new Response(200, [], json_encode(['data' => [], 'paging' => []], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new FlowsEndpoint($this->createTransport($client), '123');

        $result = $endpoint->listFlows('waba123');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/waba123/flows', (string) $request->getUri());
        self::assertSame('GET', $request->getMethod());
        self::assertIsArray($result->payload);
    }

    public function testUpdateFlowMetadata(): void
    {
        $response = new Response(200, [], json_encode(['success' => true], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new FlowsEndpoint($this->createTransport($client), '123');

        $endpoint->updateFlowMetadata('flow123', 'Updated Flow', ['BOOKING']);

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/flow123', (string) $request->getUri());
        self::assertSame('POST', $request->getMethod());
    }

    public function testPublishFlow(): void
    {
        $response = new Response(200, [], json_encode(['success' => true], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new FlowsEndpoint($this->createTransport($client), '123');

        $endpoint->publishFlow('flow123');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/flow123/publish', (string) $request->getUri());
        self::assertSame('POST', $request->getMethod());
    }

    public function testDeprecateFlow(): void
    {
        $response = new Response(200, [], json_encode(['success' => true], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new FlowsEndpoint($this->createTransport($client), '123');

        $endpoint->deprecateFlow('flow123');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/flow123/deprecate', (string) $request->getUri());
        self::assertSame('POST', $request->getMethod());
    }

    public function testDeleteFlow(): void
    {
        $response = new Response(200, [], json_encode(['success' => true], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new FlowsEndpoint($this->createTransport($client), '123');

        $endpoint->deleteFlow('flow123');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/flow123', (string) $request->getUri());
        self::assertSame('DELETE', $request->getMethod());
    }

    public function testListAssets(): void
    {
        $response = new Response(200, [], json_encode(['assets' => []], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new FlowsEndpoint($this->createTransport($client), '123');

        $endpoint->listAssets('flow123');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/flow123/assets', (string) $request->getUri());
        self::assertSame('GET', $request->getMethod());
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
