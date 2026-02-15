<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\QrCode;

final readonly class QrCodesResponse
{
    /**
     * @param list<QrCode> $qrCodes
     */
    public function __construct(public array $qrCodes)
    {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        if (!isset($payload['data']) || !is_array($payload['data'])) {
            throw new \RuntimeException('QR codes response missing data.');
        }

        $items = [];
        foreach ($payload['data'] as $item) {
            if (!is_array($item)) {
                continue;
            }
            $items[] = QrCode::fromArray($item);
        }

        return new self($items);
    }
}
