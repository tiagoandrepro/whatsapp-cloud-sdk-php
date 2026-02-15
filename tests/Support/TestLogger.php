<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Tests\Support;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

final class TestLogger implements LoggerInterface
{
    public ?string $lastLevel = null;
    public ?string $lastMessage = null;
    /** @var array<string, mixed>|null */
    public ?array $lastContext = null;

    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->record(LogLevel::EMERGENCY, (string) $message, $context);
    }

    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->record(LogLevel::ALERT, (string) $message, $context);
    }

    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->record(LogLevel::CRITICAL, (string) $message, $context);
    }

    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->record(LogLevel::ERROR, (string) $message, $context);
    }

    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->record(LogLevel::WARNING, (string) $message, $context);
    }

    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->record(LogLevel::NOTICE, (string) $message, $context);
    }

    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->record(LogLevel::INFO, (string) $message, $context);
    }

    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->record(LogLevel::DEBUG, (string) $message, $context);
    }

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->record((string) $level, (string) $message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    private function record(string $level, string $message, array $context): void
    {
        $this->lastLevel = $level;
        $this->lastMessage = $message;
        $this->lastContext = $context;
    }
}
