<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Tests\Endpoint;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tiagoandrepro\WhatsAppCloud\Auth\StaticTokenProvider;
use Tiagoandrepro\WhatsAppCloud\Endpoint\ResumableUploadEndpoint;
use Tiagoandrepro\WhatsAppCloud\Serializer\JsonSerializer;
use Tiagoandrepro\WhatsAppCloud\Tests\Support\MockPsr18Client;
use Tiagoandrepro\WhatsAppCloud\Transport\Psr18Transport;
use Tiagoandrepro\WhatsAppCloud\Util\ErrorMapper;
use Tiagoandrepro\WhatsAppCloud\Util\RetryPolicy;
use Tiagoandrepro\WhatsAppCloud\Util\SafeLogger;

final class ResumableUploadEndpointTest extends TestCase
{
    public function testCreateUploadSession(): void
    {
        $response = new Response(200, [], json_encode(['id' => 'upload:1'], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new ResumableUploadEndpoint($this->createTransport($client), '123');

        $endpoint->createUploadSession(14502, 'image/jpeg', 'profile.jpg');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame(
            'https://graph.facebook.com/v24.0/app/uploads?file_length=14502&file_type=image%2Fjpeg&file_name=profile.jpg',
            (string) $request->getUri()
        );
    }

    public function testUploadData(): void
    {
        $response = new Response(200, [], json_encode(['h' => 'handle'], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new ResumableUploadEndpoint($this->createTransport($client), '123');

        $endpoint->uploadData('upload:1', 'payload', 'image/jpeg');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame('image/jpeg', $request->getHeaderLine('Content-Type'));
        self::assertSame('0', $request->getHeaderLine('file_offset'));
        self::assertSame('payload', (string) $request->getBody());
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
