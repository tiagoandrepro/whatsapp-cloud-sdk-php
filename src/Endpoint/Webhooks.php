<?php

declare(strict_types=1);

namespace Tiagoandrepro\WhatsAppCloud\Endpoint;

use Tiagoandrepro\WhatsAppCloud\DTO\Webhook\Notification;
use Tiagoandrepro\WhatsAppCloud\Serializer\JsonSerializer;

final class Webhooks
{
    private JsonSerializer $serializer;

    public function __construct(?JsonSerializer $serializer = null)
    {
        $this->serializer = $serializer ?? new JsonSerializer();
    }

    /**
     * @param array<string, mixed>|string $payload
     * @return list<Notification>
     */
    public function parseNotifications(array|string $payload): array
    {
        $data = is_string($payload) ? $this->serializer->decode($payload) : $payload;
        $notifications = [];

        if (!isset($data['entry']) || !is_array($data['entry'])) {
            return $notifications;
        }

        foreach ($data['entry'] as $entry) {
            if (!is_array($entry)) {
                continue;
            }
            $entryId = isset($entry['id']) ? (string) $entry['id'] : null;
            if (!isset($entry['changes']) || !is_array($entry['changes'])) {
                continue;
            }
            foreach ($entry['changes'] as $change) {
                if (!is_array($change) || !isset($change['field'], $change['value']) || !is_array($change['value'])) {
                    continue;
                }
                $notifications[] = new Notification((string) $change['field'], $change['value'], $entryId);
            }
        }

        return $notifications;
    }
}
