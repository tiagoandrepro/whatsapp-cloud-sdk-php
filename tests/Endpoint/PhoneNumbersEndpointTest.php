<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Tests\Endpoint;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tiagoandrepro\WhatsAppCloud\Auth\StaticTokenProvider;
use Tiagoandrepro\WhatsAppCloud\Endpoint\PhoneNumbersEndpoint;
use Tiagoandrepro\WhatsAppCloud\Serializer\JsonSerializer;
use Tiagoandrepro\WhatsAppCloud\Tests\Support\MockPsr18Client;
use Tiagoandrepro\WhatsAppCloud\Transport\Psr18Transport;
use Tiagoandrepro\WhatsAppCloud\Util\ErrorMapper;
use Tiagoandrepro\WhatsAppCloud\Util\RetryPolicy;
use Tiagoandrepro\WhatsAppCloud\Util\SafeLogger;

final class PhoneNumbersEndpointTest extends TestCase
{
    public function testListAllWithFieldsAndFiltering(): void
    {
        $response = new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new PhoneNumbersEndpoint($this->createTransport($client), '123');

        $endpoint->listAll('waba', ['id', 'display_phone_number'], "[{\"field\":\"account_mode\",\"operator\":\"EQUAL\",\"value\":\"SANDBOX\"}]");

        $request = $client->getLastRequest();
        self::assertNotNull($request);
        self::assertSame(
            'https://graph.facebook.com/v24.0/waba/phone_numbers?fields=id%2Cdisplay_phone_number&filtering=%5B%7B%22field%22%3A%22account_mode%22%2C%22operator%22%3A%22EQUAL%22%2C%22value%22%3A%22SANDBOX%22%7D%5D',
            (string) $request->getUri()
        );
    }

    public function testRequestVerificationCode(): void
    {
        $response = new Response(200, [], json_encode(['success' => true], JSON_THROW_ON_ERROR));
        $client = new MockPsr18Client([$response]);
        $endpoint = new PhoneNumbersEndpoint($this->createTransport($client), '123');

        $endpoint->requestVerificationCode('456', 'sms', 'en_US');

        $request = $client->getLastRequest();
        self::assertNotNull($request);

        $body = json_decode((string) $request->getBody(), true);
        self::assertSame('SMS', $body['code_method']);
        self::assertSame('en_US', $body['locale']);
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
