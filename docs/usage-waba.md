# WABA Accounts

Retrieve WABA information and list owned/shared WABAs for a business.

## Get WABA

```php
<?php

declare(strict_types=1);

$result = $client->wabas()->getWaba('<waba-id>');
print_r($result->payload);
```

## Get Owned WABAs

```php
<?php

declare(strict_types=1);

$result = $client->wabas()->getOwnedWabas('<business-id>');
print_r($result->payload);
```

## Get Shared WABAs

```php
<?php

declare(strict_types=1);

$result = $client->wabas()->getSharedWabas('<business-id>');
print_r($result->payload);
```
