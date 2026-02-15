<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Auth;

final class StaticTokenProvider implements TokenProviderInterface
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
