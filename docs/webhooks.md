# Webhooks

## Parse Notifications

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$payload = file_get_contents('php://input');
$notifications = $client->webhooks()->parseNotifications($payload);

foreach ($notifications as $notification) {
	if ($notification->field === 'messages') {
		// Process incoming messages or statuses here.
	}
}
```
