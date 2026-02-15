# Phone Numbers

Use these endpoints to list phone numbers, verify ownership, and manage two-step verification codes.

## List Phone Numbers

```php
<?php

declare(strict_types=1);

$result = $client->phoneNumbers()->listAll('<waba-id>');
foreach ($result->phoneNumbers as $phoneNumber) {
    echo $phoneNumber->id . PHP_EOL;
}
```

## Get Phone Number By ID

```php
<?php

declare(strict_types=1);

$result = $client->phoneNumbers()->getById('<phone-number-id>');
echo $result->phoneNumber->displayPhoneNumber;
```

## Get Display Name Status

```php
<?php

declare(strict_types=1);

$status = $client->phoneNumbers()->getDisplayNameStatus('<phone-number-id>');
echo $status->nameStatus;
```

## Request Verification Code

```php
<?php

declare(strict_types=1);

$client->phoneNumbers()->requestVerificationCode('<phone-number-id>', 'SMS', 'en_US');
```

## Verify Code

```php
<?php

declare(strict_types=1);

$client->phoneNumbers()->verifyCode('<phone-number-id>', '123456');
```

## Set Two-Step Verification Code

```php
<?php

declare(strict_types=1);

$client->phoneNumbers()->setTwoStepVerificationCode('<phone-number-id>', '123456');
```
