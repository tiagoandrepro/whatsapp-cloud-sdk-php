<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\DTO\BlockUsers;

final readonly class BlockedUsersResponse
{
    /**
     * @param list<BlockedUser> $users
     * @param array<string, mixed>|null $paging
     */
    public function __construct(public array $users, public ?array $paging)
    {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        if (!isset($payload['data']) || !is_array($payload['data'])) {
            throw new \RuntimeException('Blocked users response missing data.');
        }

        $users = [];
        foreach ($payload['data'] as $item) {
            if (!is_array($item)) {
                continue;
            }
            $users[] = BlockedUser::fromArray($item);
        }

        $paging = isset($payload['paging']) && is_array($payload['paging']) ? $payload['paging'] : null;

        return new self($users, $paging);
    }
}
