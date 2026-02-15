```markdown
# Billing

Retrieve credit line information for your WhatsApp Business Account.

## Get Credit Lines

```php
<?php

declare(strict_types=1);

$credits = $client->billing()->getCreditLines('<business-id>');
foreach ($credits->payload['data'] as $line) {
    echo $line['id'] . ': ' . $line['balance'] . PHP_EOL;
}
```

## Get Credit Lines With Fields

```php
<?php

declare(strict_types=1);

$credits = $client->billing()->getCreditLines(
    businessId: '<business-id>',
    fields: ['id', 'balance', 'credit_type', 'display_string', 'owner', 'type']
);
```

```
