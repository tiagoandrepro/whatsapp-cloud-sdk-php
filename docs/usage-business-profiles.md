# Business Profiles

Retrieve and update the business profile for the configured phone number.

## Get Business Profile

```php
<?php

declare(strict_types=1);

$result = $client->businessProfiles()->getProfile([
    'about',
    'address',
    'description',
    'email',
    'profile_picture_url',
    'websites',
    'vertical',
]);

print_r($result->payload);
```

## Update Business Profile

```php
<?php

declare(strict_types=1);

$result = $client->businessProfiles()->updateProfile([
    'about' => 'Support available 24/7',
    'email' => 'support@example.com',
    'websites' => [
        'https://example.com',
    ],
]);
```

## Update Profile Picture (with upload handle)

```php
<?php

declare(strict_types=1);

$result = $client->businessProfiles()->updateProfile([
    'profile_picture_handle' => '<UPLOAD_HANDLE>',
]);
```
