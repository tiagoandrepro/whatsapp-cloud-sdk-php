<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\Message;

enum MediaType: string
{
    case Image = 'image';
    case Document = 'document';
    case Video = 'video';
}
