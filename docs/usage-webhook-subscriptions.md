# Webhook Subscriptions

Use these endpoints to subscribe your app to a WABA and manage webhook overrides.

## Subscribe to a WABA

```php
<?php

declare(strict_types=1);

$client->webhookSubscriptions()->subscribe('<waba-id>');
```

## List Subscriptions

```php
<?php

declare(strict_types=1);

$result = $client->webhookSubscriptions()->listSubscriptions('<waba-id>');
print_r($result->payload);
```

## Unsubscribe from a WABA

```php
<?php

declare(strict_types=1);

$client->webhookSubscriptions()->unsubscribe('<waba-id>');
```

## Override Callback URL

```php
<?php

declare(strict_types=1);

$result = $client->webhookSubscriptions()->overrideCallback(
    '<waba-id>',
    'https://example.com/whatsapp/webhook',
    'my-verify-token'
);
```
