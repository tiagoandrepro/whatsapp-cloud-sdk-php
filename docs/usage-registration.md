# Registration

Use these endpoints to register or deregister a phone number.

## Register Phone Number

```php
<?php

declare(strict_types=1);

$success = $client->registration()->register('123456');
```

## Deregister Phone Number

```php
<?php

declare(strict_types=1);

$success = $client->registration()->deregister();
```
