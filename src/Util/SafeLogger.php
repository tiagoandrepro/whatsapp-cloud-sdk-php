<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Util;

use Psr\Log\LoggerInterface;

final class SafeLogger
{
    private ?LoggerInterface $logger;
    private Redactor $redactor;

    public function __construct(?LoggerInterface $logger, ?Redactor $redactor = null)
    {
        $this->logger = $logger;
        $this->redactor = $redactor ?? new Redactor();
    }

    /**
     * @param array<string, mixed> $context
     */
    public function info(string $message, array $context = []): void
    {
        if ($this->logger === null) {
            return;
        }

        $this->logger->info($message, $this->redactor->sanitizeContext($context));
    }

    /**
     * @param array<string, mixed> $context
     */
    public function warning(string $message, array $context = []): void
    {
        if ($this->logger === null) {
            return;
        }

        $this->logger->warning($message, $this->redactor->sanitizeContext($context));
    }

    /**
     * @param array<string, mixed> $context
     */
    public function debug(string $message, array $context = []): void
    {
        if ($this->logger === null) {
            return;
        }

        $this->logger->debug($message, $this->redactor->sanitizeContext($context));
    }
}
