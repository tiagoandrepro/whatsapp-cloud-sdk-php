# Media Usage

## Upload File

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$upload = $client->media()->uploadFile(
	filePath: '/path/to/image.jpg',
	mimeType: 'image/jpeg'
);
```

## Download Media

```php
<?php

declare(strict_types=1);

use WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
	token: '<access-token>',
	phoneNumberId: '<phone-number-id>'
);

$media = $client->media()->retrieveUrl('<media-id>');
$response = $client->media()->download($media->url);

file_put_contents('media.bin', (string) $response->getBody());
```
