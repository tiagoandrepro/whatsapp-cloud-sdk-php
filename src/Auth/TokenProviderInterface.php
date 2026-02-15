<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Auth;

interface TokenProviderInterface
{
    public function getToken(): string;
}
