<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Tests\Serializer;

use PHPUnit\Framework\TestCase;
use Tiagoandrepro\WhatsAppCloud\Serializer\JsonSerializer;

final class JsonSerializerTest extends TestCase
{
    public function testEncodeDecodeRoundTrip(): void
    {
        $serializer = new JsonSerializer();
        $payload = ['foo' => 'bar'];

        $encoded = $serializer->encode($payload);
        $decoded = $serializer->decode($encoded);

        self::assertSame($payload, $decoded);
    }

    public function testDecodeInvalidJsonThrows(): void
    {
        $serializer = new JsonSerializer();

        $this->expectException(\RuntimeException::class);
        $serializer->decode('invalid');
    }
}
