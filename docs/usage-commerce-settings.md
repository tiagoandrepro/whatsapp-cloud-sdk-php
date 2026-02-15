# Commerce Settings

Get or update WhatsApp Commerce settings for the configured phone number.

## Get Settings

```php
<?php

declare(strict_types=1);

$result = $client->commerceSettings()->getSettings();
foreach ($result->items as $item) {
    var_dump($item->isCartEnabled, $item->isCatalogVisible);
}
```

## Update Settings

```php
<?php

declare(strict_types=1);

$client->commerceSettings()->updateSettings(
    isCartEnabled: true,
    isCatalogVisible: true
);
```
