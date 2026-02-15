# Templates Usage

## List Templates

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
    token: '<access-token>',
    phoneNumberId: '<phone-number-id>'
);

$result = $client->templates()->listAll('<waba-id>');
```

## Get Template by Name

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
    token: '<access-token>',
    phoneNumberId: '<phone-number-id>'
);

$result = $client->templates()->getByName('<waba-id>', 'hello_world');
```

## Create Template

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
    token: '<access-token>',
    phoneNumberId: '<phone-number-id>'
);

$payload = [
    'name' => 'sample_template',
    'language' => 'en_US',
    'category' => 'MARKETING',
    'components' => [
        [
            'type' => 'BODY',
            'text' => 'Hello {{1}}, this is a template message.',
        ],
    ],
];

$result = $client->templates()->create('<waba-id>', $payload);
```

## Update Template

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
    token: '<access-token>',
    phoneNumberId: '<phone-number-id>'
);

$payload = [
    'category' => 'MARKETING',
    'components' => [
        [
            'type' => 'BODY',
            'text' => 'Updated template content.',
        ],
    ],
];

$result = $client->templates()->update('<template-id>', $payload);
```

## Delete Template by Name

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
    token: '<access-token>',
    phoneNumberId: '<phone-number-id>'
);

$deleted = $client->templates()->deleteByName('<waba-id>', 'sample_template');
```

## Delete Template by ID

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
    token: '<access-token>',
    phoneNumberId: '<phone-number-id>'
);

$deleted = $client->templates()->deleteById('<waba-id>', '<hsm-id>', 'sample_template');
```
