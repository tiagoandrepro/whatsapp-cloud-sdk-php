# QR Codes

Create, list, update, and delete QR codes for the configured phone number.

## List QR Codes

```php
<?php

declare(strict_types=1);

$result = $client->qrCodes()->listQrCodes();
foreach ($result->qrCodes as $qrCode) {
    echo $qrCode->code . PHP_EOL;
}
```

## List QR Codes With Fields

```php
<?php

declare(strict_types=1);

$result = $client->qrCodes()->listQrCodes([
    'code',
    'prefilled_message',
    'qr_image_url.format(SVG)',
]);
```

## Get QR Code

```php
<?php

declare(strict_types=1);

$qr = $client->qrCodes()->getQrCode('ANED2T5QRU7HG1');
```

## Create QR Code

```php
<?php

declare(strict_types=1);

$qr = $client->qrCodes()->createQrCode('Show me Cyber Monday deals!', 'SVG');
```

## Update QR Code

```php
<?php

declare(strict_types=1);

$qr = $client->qrCodes()->updateQrCode('ANED2T5QRU7HG1', 'Tell me more about your offers');
```

## Delete QR Code

```php
<?php

declare(strict_types=1);

$client->qrCodes()->deleteQrCode('ANED2T5QRU7HG1');
```
