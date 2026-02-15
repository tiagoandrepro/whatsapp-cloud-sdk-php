<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Client;

use Psr\Log\LoggerInterface;
use Tiagoandrepro\WhatsAppCloud\Auth\TokenProviderInterface;
use Tiagoandrepro\WhatsAppCloud\Util\RetryPolicy;

final class ClientConfig
{
    private string $baseUrl;
    private string $graphApiVersion;
    private string $phoneNumberId;
    private TokenProviderInterface $tokenProvider;
    private RetryPolicy $retryPolicy;
    private ?LoggerInterface $logger;
    /** @var list<string> */
    private array $allowedHosts;
    private float $connectTimeoutSeconds;
    private float $totalTimeoutSeconds;

    /**
     * @param list<string> $allowedHosts
     */
    public function __construct(
        string $baseUrl,
        string $graphApiVersion,
        string $phoneNumberId,
        TokenProviderInterface $tokenProvider,
        RetryPolicy $retryPolicy,
        ?LoggerInterface $logger,
        array $allowedHosts,
        float $connectTimeoutSeconds,
        float $totalTimeoutSeconds
    ) {
        $this->baseUrl = $this->validateBaseUrl($baseUrl, $allowedHosts);
        $this->graphApiVersion = $graphApiVersion;
        $this->phoneNumberId = $phoneNumberId;
        $this->tokenProvider = $tokenProvider;
        $this->retryPolicy = $retryPolicy;
        $this->logger = $logger;
        $this->allowedHosts = $allowedHosts;
        $this->connectTimeoutSeconds = $connectTimeoutSeconds;
        $this->totalTimeoutSeconds = $totalTimeoutSeconds;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getGraphApiVersion(): string
    {
        return $this->graphApiVersion;
    }

    public function getPhoneNumberId(): string
    {
        return $this->phoneNumberId;
    }

    public function getTokenProvider(): TokenProviderInterface
    {
        return $this->tokenProvider;
    }

    public function getRetryPolicy(): RetryPolicy
    {
        return $this->retryPolicy;
    }

    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @return list<string>
     */
    public function getAllowedHosts(): array
    {
        return $this->allowedHosts;
    }

    public function getConnectTimeoutSeconds(): float
    {
        return $this->connectTimeoutSeconds;
    }

    public function getTotalTimeoutSeconds(): float
    {
        return $this->totalTimeoutSeconds;
    }

    /**
     * @param list<string> $allowedHosts
     */
    private function validateBaseUrl(string $baseUrl, array $allowedHosts): string
    {
        $parts = parse_url($baseUrl);
        if ($parts === false || !isset($parts['scheme'], $parts['host'])) {
            throw new \InvalidArgumentException('Base URL is invalid.');
        }

        if (strtolower((string) $parts['scheme']) !== 'https') {
            throw new \InvalidArgumentException('Base URL must use HTTPS.');
        }

        $host = strtolower((string) $parts['host']);
        $allowed = array_map('strtolower', $allowedHosts);
        if (!in_array($host, $allowed, true)) {
            throw new \InvalidArgumentException('Base URL host is not allowlisted.');
        }

        return rtrim($baseUrl, '/');
    }
}
