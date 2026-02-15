<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Tests\Util;

use PHPUnit\Framework\TestCase;
use Tiagoandrepro\WhatsAppCloud\Tests\Support\TestLogger;
use Tiagoandrepro\WhatsAppCloud\Util\Redactor;
use Tiagoandrepro\WhatsAppCloud\Util\SafeLogger;

final class SafeLoggerTest extends TestCase
{
    public function testRedactsSensitiveContext(): void
    {
        $logger = new TestLogger();
        $safeLogger = new SafeLogger($logger, new Redactor());

        $safeLogger->info('test', [
            'Authorization' => 'Bearer secret',
            'token' => 'secret',
            'phone' => '+15551234567',
        ]);

        self::assertNotNull($logger->lastContext);
        self::assertSame('[redacted]', $logger->lastContext['Authorization']);
        self::assertSame('[redacted]', $logger->lastContext['token']);
        self::assertSame('[redacted-number]', $logger->lastContext['phone']);
    }
}
