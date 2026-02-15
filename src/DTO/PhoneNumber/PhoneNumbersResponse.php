<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\PhoneNumber;

final readonly class PhoneNumbersResponse
{
    /**
     * @param list<PhoneNumber> $phoneNumbers
     */
    public function __construct(public array $phoneNumbers)
    {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        if (!isset($payload['data']) || !is_array($payload['data'])) {
            throw new \RuntimeException('Phone numbers response missing data.');
        }

        $items = [];
        foreach ($payload['data'] as $item) {
            if (!is_array($item)) {
                continue;
            }
            $items[] = PhoneNumber::fromArray($item);
        }

        return new self($items);
    }
}
