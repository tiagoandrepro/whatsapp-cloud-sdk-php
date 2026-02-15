# Quick Start Guide

Get started with the WhatsApp Cloud API SDK in 5 minutes.

## 1. Install

```bash
composer require tiagoandrepro/whatsapp-cloud-sdk-php guzzlehttp/guzzle
```

## 2. Get Credentials

1. Create a [Meta App](https://developers.facebook.com/apps)
2. Add WhatsApp product
3. Get your:
   - **Access Token** (`WHATSAPP_TOKEN`)
   - **Phone Number ID** (`WHATSAPP_PHONE_ID`)

## 3. Send Your First Message

```php
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Tiagoandrepro\WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
    token: 'your_access_token',
    phoneNumberId: 'your_phone_id'
);

try {
    $response = $client->messages()->sendText(
        to: '+5511987654321',
        text: '👋 Hello from WhatsApp Cloud API!'
    );
    
    echo "Message sent! ID: " . $response->getMessageId() . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

## 4. Common Tasks

### Send Text Message

```php
$response = $client->messages()->sendText(
    to: '+5511987654321',
    text: 'Hello! How are you?'
);

echo $response->getMessageId();
```

### Send Image

```php
use Tiagoandrepro\WhatsAppCloud\DTO\Message\Media\LinkID;

$response = $client->messages()->sendImage(
    to: '+5511987654321',
    media: new LinkID('https://example.com/image.jpg'),
    caption: 'Check this out!'
);

echo $response->getMessageId();
```

### Send Template Message

```php
$response = $client->templates()->sendTemplate(
    phoneNumberId: 'your_phone_id',
    to: '+5511987654321',
    templateName: 'hello_world',
    languageCode: 'en_US'
);

echo $response->getMessageId();
```

### Upload Media

```php
$response = $client->media()->upload(
    filePath: '/path/to/file.jpg',
    mimeType: 'image/jpeg'
);

echo $response->getId(); // Use this ID in messages
```

### Mark Message as Read

```php
$response = $client->messages()->markAsRead(
    messageId: 'message_id_to_read'
);

echo $response->isSuccess();
```

### Get Business Profile

```php
$profile = $client->businessProfiles()->get(
    fields: 'about,email,profile_picture_url'
);

echo $profile->getAbout();
```

## 5. Error Handling

```php
use Tiagoandrepro\WhatsAppCloud\Exception\{
    ValidationException,
    AuthException,
    NotFoundException,
    RateLimitException,
    ServerException
};

try {
    $response = $client->messages()->sendText(
        to: '+5511987654321',
        text: 'Hello!'
    );
} catch (ValidationException $e) {
    // Invalid input (400/422)
    echo "Validation error: " . $e->getMessage();
} catch (AuthException $e) {
    // Auth failed (401/403)
    echo "Authentication error: " . $e->getMessage();
} catch (RateLimitException $e) {
    // Rate limited (429)
    $retryAfter = $e->getRetryAfterSeconds() ?? 60;
    echo "Rate limited. Retry after {$retryAfter}s";
} catch (NotFoundException $e) {
    // Resource not found (404)
    echo "Not found: " . $e->getMessage();
} catch (ServerException $e) {
    // Server error (5xx)
    echo "Server error: " . $e->getMessage();
}
```

## 6. Configuration

### Custom HTTP Client

```php
use GuzzleHttp\Client as GuzzleClient;

$httpClient = new GuzzleClient([
    'verify' => true,
    'timeout' => 30,
]);

$client = WhatsAppClient::fromDefaults(
    token: 'your_token',
    phoneNumberId: 'your_phone_id',
    httpClient: $httpClient
);
```

### Custom Logger

```php
use Monolog\Logger;
use Monolog\Handlers\StreamHandler;

$logger = new Logger('whatsapp');
$logger->pushHandler(new StreamHandler('logs/whatsapp.log'));

$client = WhatsAppClient::fromDefaults(
    token: 'your_token',
    phoneNumberId: 'your_phone_id',
    logger: $logger
);
```

### Different API Version

```php
$client = WhatsAppClient::fromDefaults(
    token: 'your_token',
    phoneNumberId: 'your_phone_id',
    graphApiVersion: 'v23.0'  // Default is v24.0
);
```

## 7. Webhooks (Receiving Messages)

```php
<?php

use Tiagoandrepro\WhatsAppCloud\Endpoint\Webhooks;

$webhooks = new Webhooks();

// Verify webhook (during setup)
if (!empty($_GET)) {
    echo $webhooks->verify(
        $_GET,
        'your_verify_token_from_dashboard'
    );
    exit;
}

// Receive messages
$payload = json_decode(file_get_contents('php://input'), true);

if (!empty($payload)) {
    $notifications = $webhooks->readAll($payload);
    
    foreach ($notifications as $notification) {
        $from = $notification->getSender();
        $messageType = $notification->getMessageType();
        
        // Handle different message types
        // See WEBHOOK_NOTIFICATIONS.md for details
    }
    
    http_response_code(200);
}
```

## 8. Testing

```bash
# Run all tests
composer test

# Check code style
./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php

# Run static analysis
composer stan

# Validate composer
composer validate --no-interaction --strict
```

## 9. Environment Variables

```bash
# .env file
WHATSAPP_TOKEN=your_access_token
WHATSAPP_PHONE_ID=your_phone_id
WHATSAPP_API_VERSION=v24.0
```

```php
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = WhatsAppClient::fromDefaults(
    token: $_ENV['WHATSAPP_TOKEN'],
    phoneNumberId: $_ENV['WHATSAPP_PHONE_ID']
);
```

## 10. Next Steps

- 📖 Read [INSTALLATION.md](INSTALLATION.md) for detailed setup
- 🔧 Check [docs/configuration.md](docs/configuration.md) for all options
- 📞 Explore usage guides in [docs/](docs/)
- 🔒 Review [SECURITY.md](SECURITY.md) for best practices
- 🚨 Learn error handling in [docs/errors.md](docs/errors.md)
- 🏗️ Understand architecture in [docs/architecture.md](docs/architecture.md)

## Support

- 🐛 [Report Issues](https://github.com/tiagoandrepro/whatsapp-cloud-sdk-php/issues)
- 💬 [Ask Questions](https://github.com/tiagoandrepro/whatsapp-cloud-sdk-php/discussions)
- 📚 [Full Docs](docs/)
- 📞 [WhatsApp API Docs](https://developers.facebook.com/docs/whatsapp/cloud-api)
