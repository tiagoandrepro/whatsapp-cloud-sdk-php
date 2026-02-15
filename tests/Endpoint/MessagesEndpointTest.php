<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Tests\Endpoint;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tiagoandrepro\WhatsAppCloud\Auth\StaticTokenProvider;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive\ListRow;
use Tiagoandrepro\WhatsAppCloud\DTO\Message\Interactive\ListSection;
use Tiagoandrepro\WhatsAppCloud\Endpoint\MessagesEndpoint;
use Tiagoandrepro\WhatsAppCloud\Serializer\JsonSerializer;
use Tiagoandrepro\WhatsAppCloud\Tests\Support\MockPsr18Client;
use Tiagoandrepro\WhatsAppCloud\Transport\Psr18Transport;
use Tiagoandrepro\WhatsAppCloud\Util\ErrorMapper;
use Tiagoandrepro\WhatsAppCloud\Util\RetryPolicy;
use Tiagoandrepro\WhatsAppCloud\Util\SafeLogger;

final class MessagesEndpointTest extends TestCase
{
    public function testSendText(): void
    {
        $response = new Response(200, [], json_encode(['messages' => [['id' => 'wamid.1']]], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $transport = $this->createTransport($client);

        $endpoint = new MessagesEndpoint($transport, '123');
        $result = $endpoint->sendText('+15551234567', 'hello');

        self::assertSame('wamid.1', $result->messageId);
    }

    public function testSendAudioWithContext(): void
    {
        $response = new Response(200, [], json_encode(['messages' => [['id' => 'wamid.2']]], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $transport = $this->createTransport($client);

        $endpoint = new MessagesEndpoint($transport, '123');
        $endpoint->sendAudio('+15551234567', link: 'https://example.com/audio.ogg', contextMessageId: 'wamid.ctx');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        $body = json_decode((string) $request->getBody(), true);

        self::assertSame('audio', $body['type']);
        self::assertSame('wamid.ctx', $body['context']['message_id']);
    }

    public function testSendListMessage(): void
    {
        $response = new Response(200, [], json_encode(['messages' => [['id' => 'wamid.3']]], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $transport = $this->createTransport($client);

        $sections = [
            new ListSection('Options', [
                new ListRow('opt_1', 'Option 1', 'First option'),
            ]),
        ];

        $endpoint = new MessagesEndpoint($transport, '123');
        $endpoint->sendList(
            to: '+15551234567',
            buttonText: 'Choose',
            bodyText: 'Pick one option',
            sections: $sections
        );

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        $body = json_decode((string) $request->getBody(), true);

        self::assertSame('interactive', $body['type']);
        self::assertSame('list', $body['interactive']['type']);
    }

    public function testSendTypingIndicator(): void
    {
        $response = new Response(200, [], json_encode(['success' => true], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $transport = $this->createTransport($client);

        $endpoint = new MessagesEndpoint($transport, '123');
        $endpoint->sendTypingIndicatorReadReceipt('wamid.HBgL');

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        $body = json_decode((string) $request->getBody(), true);

        self::assertSame('read', $body['status']);
        self::assertSame('text', $body['typing_indicator']['type']);
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
