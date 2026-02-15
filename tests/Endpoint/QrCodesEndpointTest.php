<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Tests\Endpoint;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tiagoandrepro\WhatsAppCloud\Auth\StaticTokenProvider;
use Tiagoandrepro\WhatsAppCloud\Endpoint\QrCodesEndpoint;
use Tiagoandrepro\WhatsAppCloud\Serializer\JsonSerializer;
use Tiagoandrepro\WhatsAppCloud\Tests\Support\MockPsr18Client;
use Tiagoandrepro\WhatsAppCloud\Transport\Psr18Transport;
use Tiagoandrepro\WhatsAppCloud\Util\ErrorMapper;
use Tiagoandrepro\WhatsAppCloud\Util\RetryPolicy;
use Tiagoandrepro\WhatsAppCloud\Util\SafeLogger;

final class QrCodesEndpointTest extends TestCase
{
    public function testGetQrCode(): void
    {
        $response = new Response(200, [], json_encode(['data' => [['code' => 'X']]], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new QrCodesEndpoint($this->createTransport($client), '123');

        $endpoint->getQrCode('ANED2T5QRU7HG1');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('https://graph.facebook.com/v24.0/123/message_qrdls/ANED2T5QRU7HG1', (string) $request->getUri());
    }

    public function testListQrCodesWithFields(): void
    {
        $response = new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new QrCodesEndpoint($this->createTransport($client), '123');

        $endpoint->listQrCodes(['code', 'qr_image_url.format(SVG)'], 'CODE123');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame(
            'https://graph.facebook.com/v24.0/123/message_qrdls?fields=code%2Cqr_image_url.format%28SVG%29&code=CODE123',
            (string) $request->getUri()
        );
    }

    public function testCreateQrCode(): void
    {
        $response = new Response(200, [], json_encode(['code' => 'X'], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new QrCodesEndpoint($this->createTransport($client), '123');

        $endpoint->createQrCode('Hello', 'SVG');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        $body = json_decode((string) $request->getBody(), true);
        self::assertSame('Hello', $body['prefilled_message']);
        self::assertSame('SVG', $body['generate_qr_image']);
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
