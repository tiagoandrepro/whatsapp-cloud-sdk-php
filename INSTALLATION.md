# Installation Guide

Complete guide for installing and configuring the WhatsApp Cloud API SDK.

## Requirements

- **PHP 8.4 or higher** (required for strict types and readonly properties)
- **PSR-18 HTTP Client** (you provide)
- **PSR-17 HTTP Request/Response Factories** (you provide)
- **PSR-3 Logger** (optional, for structured logging)

## Installation via Composer

### Install the SDK

```bash
composer require tiagoandrepro/whatsapp-cloud-sdk-php
```

### Install HTTP Client (Choose One)

The SDK requires a PSR-18 compliant HTTP client. Choose based on your needs:

#### Option 1: Guzzle (Recommended)

```bash
composer require guzzlehttp/guzzle nyholm/psr7
```

#### Option 2: Symfony HttpClient

```bash
composer require symfony/http-client nyholm/psr7
```

#### Option 3: Slim4 PSR-18 Client

```bash
composer require slim/psr7 slim/http
```

#### Option 4: Custom Implementation

Implement `Psr\Http\Client\ClientInterface` if you have a custom HTTP client.

### Optional: Install Logger

For structured logging of SDK operations:

```bash
composer require monolog/monolog
```

## Setup

### 1. Basic Initialization

```php
<?php

declare(strict_types=1);

use Tiagoandrepro\WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
    token: getenv('WHATSAPP_TOKEN'),
    phoneNumberId: getenv('WHATSAPP_PHONE_ID')
);

// Now ready to use
$response = $client->messages()->sendText(
    to: '+5511987654321',
    text: 'Hello from WhatsApp!'
);

echo $response->getMessageId();
```

### 2. With Custom HTTP Client (Guzzle)

```php
<?php

declare(strict_types=1);

use GuzzleHttp\Client as GuzzleClient;
use Tiagoandrepro\WhatsAppCloud\Client\WhatsAppClient;

$httpClient = new GuzzleClient([
    'verify' => true,  // Verify SSL certificates
    'timeout' => 30,   // 30 second timeout
]);

$client = WhatsAppClient::fromDefaults(
    token: getenv('WHATSAPP_TOKEN'),
    phoneNumberId: getenv('WHATSAPP_PHONE_ID'),
    httpClient: $httpClient
);

$response = $client->messages()->sendText(
    to: '+5511987654321',
    text: 'Secure message'
);
```

### 3. With Custom Logger

```php
<?php

declare(strict_types=1);

use Monolog\Logger;
use Monolog\Handlers\StreamHandler;
use Tiagoandrepro\WhatsAppCloud\Client\WhatsAppClient;

$logger = new Logger('whatsapp');
$logger->pushHandler(new StreamHandler('logs/whatsapp.log'));

$client = WhatsAppClient::fromDefaults(
    token: getenv('WHATSAPP_TOKEN'),
    phoneNumberId: getenv('WHATSAPP_PHONE_ID'),
    logger: $logger
);

// SDK will log all requests/responses with sensitive data redacted
```

### 4. Complete Setup with All Options

```php
<?php

declare(strict_types=1);

use GuzzleHttp\Client as GuzzleClient;
use Monolog\Logger;
use Monolog\Handlers\StreamHandler;
use Tiagoandrepro\WhatsAppCloud\Client\WhatsAppClient;

// Configure HTTP client
$httpClient = new GuzzleClient([
    'verify' => true,
    'timeout' => 30,
    'connect_timeout' => 10,
]);

// Configure logger
$logger = new Logger('whatsapp');
$logger->pushHandler(
    new StreamHandler('logs/whatsapp.log', Logger::DEBUG)
);

// Create client
$client = WhatsAppClient::fromDefaults(
    token: getenv('WHATSAPP_TOKEN'),
    phoneNumberId: getenv('WHATSAPP_PHONE_ID'),
    graphApiVersion: 'v24.0',  // Optional
    baseUrl: 'https://graph.facebook.com',  // Optional
    httpClient: $httpClient,
    logger: $logger
);
```

## Environment Configuration

### .env File

```bash
# WhatsApp Configuration
WHATSAPP_TOKEN=your_access_token_here
WHATSAPP_PHONE_ID=your_phone_number_id_here

# Optional
WHATSAPP_BUSINESS_ACCOUNT_ID=your_waba_id
WHATSAPP_API_VERSION=v24.0
GRAPH_BASE_URL=https://graph.facebook.com
```

### Load from .env

```php
<?php

declare(strict_types=1);

// Using vlucas/dotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Tiagoandrepro\WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
    token: (string)$_ENV['WHATSAPP_TOKEN'],
    phoneNumberId: (string)$_ENV['WHATSAPP_PHONE_ID']
);
```

## Verification

Test your installation:

```php
<?php

declare(strict_types=1);

use Tiagoandrepro\WhatsAppCloud\Client\WhatsAppClient;

try {
    $client = WhatsAppClient::fromDefaults(
        token: getenv('WHATSAPP_TOKEN'),
        phoneNumberId: getenv('WHATSAPP_PHONE_ID')
    );
    
    // Get phone number info (simple test)
    $response = $client->phoneNumbers()->getPhoneNumber(
        fields: 'display_name,status'
    );
    
    echo "✓ SDK installation successful!\n";
    echo "Phone: " . $response->getDisplayName() . "\n";
    echo "Status: " . $response->getStatus() . "\n";
    
} catch (\Exception $e) {
    echo "✗ Installation error: " . $e->getMessage() . "\n";
    exit(1);
}
```

## Framework Integration

### Laravel

```php
// config/whatsapp.php
return [
    'token' => env('WHATSAPP_TOKEN'),
    'phone_id' => env('WHATSAPP_PHONE_ID'),
];

// Service Provider
use Tiagoandrepro\WhatsAppCloud\Client\WhatsAppClient;

public function register()
{
    $this->app->singleton(WhatsAppClient::class, function ($app) {
        return WhatsAppClient::fromDefaults(
            token: config('whatsapp.token'),
            phoneNumberId: config('whatsapp.phone_id')
        );
    });
}

// Usage in Controller
class WhatsAppController
{
    public function __construct(private WhatsAppClient $client) {}
    
    public function send(Request $request)
    {
        $response = $this->client->messages()->sendText(
            to: $request->input('phone'),
            text: $request->input('message')
        );
        
        return response()->json($response->toArray());
    }
}
```

### Symfony

```yaml
# config/services.yaml
services:
  Tiagoandrepro\WhatsAppCloud\Client\WhatsAppClient:
    factory:
      class: Tiagoandrepro\WhatsAppCloud\Client\WhatsAppClient
      method: 'fromDefaults'
    arguments:
      token: '%env(WHATSAPP_TOKEN)%'
      phoneNumberId: '%env(WHATSAPP_PHONE_ID)%'
```

## Troubleshooting

### Common Issues

**"Class not found"**
- Verify `composer install` ran successfully
- Check autoloader: `composer dump-autoload`

**"HTTP Client not found"**
- Install a PSR-18 client: `composer require guzzlehttp/guzzle`

**"Permission denied"**
- Check directory permissions
- For PHP-CS-Fixer: `vendor/bin/php-cs-fixer`

**"SSL Certificate error"**
- Update CA certificates
- Or disable verification in development (NOT recommended for production)

See [TROUBLESHOOTING.md](TROUBLESHOOTING.md) for more help.

## Next Steps

- 📖 Read [QUICK_START.md](QUICK_START.md) for your first message
- 🔧 Check [docs/configuration.md](docs/configuration.md) for options
- 📞 Review usage guides in [docs/](docs/)
- 🔒 Understand [SECURITY.md](SECURITY.md) best practices

## Support

- 🐛 [Report Issues](https://github.com/tiagoandrepro/whatsapp-cloud-sdk-php/issues)
- 💬 [Start Discussion](https://github.com/tiagoandrepro/whatsapp-cloud-sdk-php/discussions)
- 📖 [Full Documentation](docs/)
