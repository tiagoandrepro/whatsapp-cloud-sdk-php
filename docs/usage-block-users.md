```markdown
# Block Users

Manage blocked users for the configured phone number.

## Get Blocked Users

```php
<?php

declare(strict_types=1);

$blocked = $client->blockUsers()->getBlockedUsers();
foreach ($blocked->payload['data'] as $user) {
    echo $user['user'] . PHP_EOL;
}
```

## Block Users

```php
<?php

declare(strict_types=1);

$result = $client->blockUsers()->blockUsers([
    '+15551234567',
    '+15559876543'
]);
```

## Unblock Users

```php
<?php

declare(strict_types=1);

$result = $client->blockUsers()->unblockUsers([
    '+15551234567'
]);
```

```
