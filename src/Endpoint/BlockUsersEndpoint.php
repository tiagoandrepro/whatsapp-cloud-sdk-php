<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Endpoint;

use Tiagoandrepro\WhatsAppCloud\DTO\BlockUsers\BlockedUsersResponse;
use Tiagoandrepro\WhatsAppCloud\DTO\BlockUsers\BlockUsersActionResponse;
use Tiagoandrepro\WhatsAppCloud\Util\Validator;

final class BlockUsersEndpoint extends AbstractEndpoint
{
    public function getBlockedUsers(): BlockedUsersResponse
    {
        $payload = $this->transport->requestJson('GET', $this->phoneNumberId . '/block_users');

        return BlockedUsersResponse::fromArray($payload);
    }

    /**
     * @param list<string> $users
     */
    public function blockUsers(array $users): BlockUsersActionResponse
    {
        if ($users === []) {
            throw new \InvalidArgumentException('users must not be empty.');
        }

        $payload = $this->transport->requestJson('POST', $this->phoneNumberId . '/block_users', $this->buildUsersPayload($users));

        return BlockUsersActionResponse::fromArray($payload);
    }

    /**
     * @param list<string> $users
     */
    public function unblockUsers(array $users): BlockUsersActionResponse
    {
        if ($users === []) {
            throw new \InvalidArgumentException('users must not be empty.');
        }

        $payload = $this->transport->requestJson('DELETE', $this->phoneNumberId . '/block_users', $this->buildUsersPayload($users));

        return BlockUsersActionResponse::fromArray($payload);
    }

    /**
     * @param list<string> $users
     * @return array<string, mixed>
     */
    private function buildUsersPayload(array $users): array
    {
        $items = [];
        foreach ($users as $user) {
            Validator::assertNotEmpty($user, 'user');
            $items[] = ['user' => $user];
        }

        return [
            'messaging_product' => 'whatsapp',
            'block_users' => $items,
        ];
    }
}
