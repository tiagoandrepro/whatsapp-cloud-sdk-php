<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Util;

final class RetryPolicy
{
    private int $maxRetries;
    private int $baseDelayMs;
    private int $maxDelayMs;

    public function __construct(int $maxRetries = 2, int $baseDelayMs = 200, int $maxDelayMs = 2000)
    {
        $this->maxRetries = $maxRetries;
        $this->baseDelayMs = $baseDelayMs;
        $this->maxDelayMs = $maxDelayMs;
    }

    public function getMaxRetries(): int
    {
        return $this->maxRetries;
    }

    public function shouldRetry(?int $statusCode): bool
    {
        if ($statusCode === null) {
            return false;
        }

        return $statusCode === 429 || ($statusCode >= 500 && $statusCode <= 599);
    }

    public function getDelayMs(int $attempt): int
    {
        $expDelay = $this->baseDelayMs * (2 ** max(0, $attempt - 1));
        $jitter = random_int(0, $this->baseDelayMs);
        return min($this->maxDelayMs, $expDelay + $jitter);
    }
}
