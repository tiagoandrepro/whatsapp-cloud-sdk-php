<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Endpoint;

use Tiagoandrepro\WhatsAppCloud\Transport\Psr18Transport;

abstract class AbstractEndpoint
{
    protected Psr18Transport $transport;
    protected string $phoneNumberId;

    public function __construct(Psr18Transport $transport, string $phoneNumberId)
    {
        $this->transport = $transport;
        $this->phoneNumberId = $phoneNumberId;
    }
}
