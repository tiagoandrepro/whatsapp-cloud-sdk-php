# Resumable Uploads

Use resumable uploads to obtain a profile picture handle for business profile updates.

## Create Upload Session

```php
<?php

declare(strict_types=1);

$session = $client->resumableUploads()->createUploadSession(
    fileLength: 14502,
    fileType: 'image/jpeg',
    fileName: 'profile.jpg'
);
```

## Upload File Data

```php
<?php

declare(strict_types=1);

$handle = $client->resumableUploads()->uploadFileData(
    uploadId: $session->id,
    filePath: __DIR__ . '/profile.jpg',
    mimeType: 'image/jpeg'
);
```

## Query Upload Status

```php
<?php

declare(strict_types=1);

$status = $client->resumableUploads()->queryUploadStatus($session->id);
```
