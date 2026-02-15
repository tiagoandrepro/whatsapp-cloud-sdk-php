<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Tests\Util;

use PHPUnit\Framework\TestCase;
use Tiagoandrepro\WhatsAppCloud\Exception\AuthException;
use Tiagoandrepro\WhatsAppCloud\Exception\RateLimitException;
use Tiagoandrepro\WhatsAppCloud\Util\ErrorMapper;

final class ErrorMapperTest extends TestCase
{
    public function testMapsAuthException(): void
    {
        $mapper = new ErrorMapper();
        $exception = $mapper->map(401, ['error' => ['message' => 'invalid']], 'GET', '/test', 'req', null);

        self::assertInstanceOf(AuthException::class, $exception);
    }

    public function testMapsRateLimitExceptionWithRetryAfter(): void
    {
        $mapper = new ErrorMapper();
        $exception = $mapper->map(429, ['error' => ['message' => 'rate limit']], 'GET', '/test', 'req', 10);

        self::assertInstanceOf(RateLimitException::class, $exception);
        self::assertSame(10, $exception->getRetryAfterSeconds());
    }
}
