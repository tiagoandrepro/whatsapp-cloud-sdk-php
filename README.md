# WhatsApp Cloud API SDK (PHP 8.4)

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![PHP 8.4+](https://img.shields.io/badge/PHP-8.4%2B-blue.svg)](https://php.net)
[![Tests](https://img.shields.io/badge/Tests-44%2F44%20passing-brightgreen.svg)]()
[![PHPStan](https://img.shields.io/badge/PHPStan-Level%20max-brightgreen.svg)]()

Production-grade PHP SDK for Meta WhatsApp Cloud API with strict typing, secure defaults, and comprehensive documentation.

## ✨ Features

- **18 Endpoints** - Complete WhatsApp Cloud API coverage
- **Strict Types** - PHP 8.4 readonly DTOs with full type hints
- **PSR Compliant** - PSR-18 HTTP Client, PSR-3 Logging, PSR-17 Factories
- **Secure by Default** - Automatic sensitive data redaction, SSRF prevention, input validation
- **Error Handling** - 6 typed exceptions for precise error catching
- **Automatic Retry** - Smart retry for transient failures (429, 5xx)
- **44 Tests** - 100% endpoint coverage with 116 assertions
- **Quality Gates** - PHPStan level max, PHP-CS-Fixer compliant
- **Well Documented** - 23 docs covering all features and use cases

## Requirements

- **PHP 8.4+** (strict types, readonly properties)
- **PSR-18 HTTP Client** (you provide - Guzzle, Symfony, etc)
- **PSR-17 Message Factories** (you provide)
- **PSR-3 Logger** (optional - Monolog, etc)

## Quick Install

```bash
composer require tiagoandrepro/whatsapp-cloud-sdk-php guzzlehttp/guzzle
```

## Your First Message

```php
<?php

declare(strict_types=1);

use Tiagoandrepro\WhatsAppCloud\Client\WhatsAppClient;

$client = WhatsAppClient::fromDefaults(
    token: getenv('WHATSAPP_TOKEN'),
    phoneNumberId: getenv('WHATSAPP_PHONE_ID')
);

$response = $client->messages()->sendText(
    to: '+5511987654321',
    text: 'Hello from WhatsApp!'
);

echo 'Message sent: ' . $response->getMessageId();
```

## Documentation

### Getting Started
- 📖 **[QUICK_START.md](QUICK_START.md)** - 5-minute quickstart with examples
- 🔧 **[INSTALLATION.md](INSTALLATION.md)** - Complete installation & setup guide
- 🚀 **[QUICK_START.md](QUICK_START.md)** - First steps with common tasks

### Configuration & Usage
- ⚙️ **[docs/configuration.md](docs/configuration.md)** - Configuration options
- 📞 **[docs/usage-messages.md](docs/usage-messages.md)** - Sending messages (text, media, templates)
- 🎬 **[docs/usage-media.md](docs/usage-media.md)** - Media upload/download
- 📋 **[docs/usage-templates.md](docs/usage-templates.md)** - Template messages
- 🪝 **[docs/webhooks.md](docs/webhooks.md)** - Receiving webhooks & parsing notifications
- 📱 **[docs/usage-registration.md](docs/usage-registration.md)** - Phone registration & verification

### Advanced Topics
- 🔐 **[SECURITY.md](SECURITY.md)** - Security best practices, data redaction, SSRF prevention
- 🏗️ **[docs/architecture.md](docs/architecture.md)** - Architecture, design decisions, components
- 📊 **[docs/compatibility.md](docs/compatibility.md)** - PHP version, PSR, framework support
- ⚡ **[docs/errors.md](docs/errors.md)** - Exception hierarchy, error handling patterns
- 📈 **[CHANGELOG.md](CHANGELOG.md)** - Version history and feature roadmap

### Support & Contributing
- 🐛 **[TROUBLESHOOTING.md](TROUBLESHOOTING.md)** - Common issues & solutions
- 🤝 **[CONTRIBUTING.md](CONTRIBUTING.md)** - How to contribute
- 📜 **[LICENSE](LICENSE)** - MIT License

### Additional Resources
- 📍 **[docs/api-map.md](docs/api-map.md)** - Complete endpoint reference
- 📚 **Full Usage Guides in [docs/](docs/)** - 18+ detailed usage examples

## Examples

### Send Text Message
```php
$response = $client->messages()->sendText(
    to: '+5511987654321',
    text: 'Hello! How are you?'
);
```

### Send Image
```php
use Tiagoandrepro\WhatsAppCloud\DTO\Message\Media\LinkID;

$response = $client->messages()->sendImage(
    to: '+5511987654321',
    media: new LinkID('https://example.com/image.jpg'),
    caption: 'Check this out!'
);
```

### Send Template
```php
$response = $client->templates()->sendTemplate(
    phoneNumberId: 'your_phone_id',
    to: '+5511987654321',
    templateName: 'hello_world',
    languageCode: 'en_US'
);
```

### Handle Errors
```php
use Tiagoandrepro\WhatsAppCloud\Exception\{
    ValidationException,
    AuthException,
    RateLimitException,
    ServerException
};

try {
    $response = $client->messages()->sendText($to, $text);
} catch (ValidationException $e) {
    // Fix input and retry
    echo "Validation error: " . $e->getMessage();
} catch (RateLimitException $e) {
    // Respect Retry-After header
    $retryAfter = $e->getRetryAfterSeconds() ?? 60;
    sleep($retryAfter);
    $response = $client->messages()->sendText($to, $text);
} catch (ServerException $e) {
    // Implement exponential backoff
    echo "Server error, retrying...";
}
```

## Quality Assurance

```bash
# Run tests
composer test

# Check code style
./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php

# Static analysis
composer stan

# Validate composer
composer validate --no-interaction --strict
```

**Results:**
- ✅ 44 PHPUnit tests (116 assertions)
- ✅ 100% endpoint coverage
- ✅ PHPStan level max
- ✅ PHP-CS-Fixer compliant
- ✅ All checks passing

## Architecture

The SDK uses a modular architecture with:

- **WhatsAppClient** - Entry point with lazy-loaded endpoints
- **Psr18Transport** - HTTP abstraction layer with retry policy
- **ErrorMapper** - Status/body to typed exceptions
- **JsonSerializer** - Safe JSON encode/decode
- **SafeLogger** - Structured logging with data redaction
- **Typed DTOs** - Readonly data transfer objects

See [docs/architecture.md](docs/architecture.md) for details.

## Security

The SDK is secure by default:

- 🔒 **Sensitive Data Redaction** - Automatic token/PII masking in logs
- 🛡️ **Input Validation** - E.164 phones, URL allowlist, token validation
- 🚫 **SSRF Prevention** - Base URL allowlist enforcement
- 🔐 **HTTPS Only** - Enforced in production
- 🔄 **Smart Retry** - Only for transient failures (429, 5xx)
- ⚠️ **Safe Exceptions** - No stack traces with sensitive data

See [SECURITY.md](SECURITY.md) for comprehensive security guide.

## Framework Integration

Works with any PHP framework:

- **Laravel** - Service Provider support
- **Symfony** - Service configuration
- **Slim 4** - Dependency injection
- **Plain PHP** - No framework required

Examples in [INSTALLATION.md](INSTALLATION.md#framework-integration).

## Comparison

**vs. netflie/whatsapp-cloud-api:**
- ✅ Modern PHP 8.4 with strict types
- ✅ Advanced features (Flows, Billing, QR Codes, Analytics)
- ✅ Better error handling (typed exceptions)
- ✅ Comprehensive documentation (23 files)
- ✅ Higher test coverage (44 tests, 100%)
- ✅ Security-first design
- ✅ Production-grade quality (PHPStan max, CS-Fixer)

See [COMPARISON.md](docs/comparison.md) for full feature comparison.

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

## Support

- 🐛 **[Report Issues](https://github.com/tiagoandrepro/whatsapp-cloud-sdk-php/issues)**
- 💬 **[Ask Questions](https://github.com/tiagoandrepro/whatsapp-cloud-sdk-php/discussions)**
- 📖 **[Full Documentation](docs/)**
- 🆘 **[Troubleshooting](TROUBLESHOOTING.md)**

## License

MIT License - see [LICENSE](LICENSE) file for details.

## References

- [Meta WhatsApp Cloud API](https://developers.facebook.com/docs/whatsapp/cloud-api)
- [Graph API Documentation](https://developers.facebook.com/docs/graph-api)
- [PSR Standards](https://www.php-fig.org/)
- [Semantic Versioning](https://semver.org/)

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history.

---

**Ready to get started?** → [Quick Start Guide](QUICK_START.md)
- Phone Numbers: docs/usage-phone-numbers.md
- Webhook Subscriptions: docs/usage-webhook-subscriptions.md
- Business Profiles: docs/usage-business-profiles.md
- Resumable Uploads: docs/usage-resumable-upload.md
- WABA Accounts: docs/usage-waba.md
- Commerce Settings: docs/usage-commerce-settings.md
- QR Codes: docs/usage-qr-codes.md
- Analytics: docs/usage-analytics.md
- Flows: docs/usage-flows.md
- Billing: docs/usage-billing.md
- Block Users: docs/usage-block-users.md
- Business Portfolio: docs/usage-business-portfolio.md
- Errors: docs/errors.md
- Compatibility: docs/compatibility.md

## Security

See SECURITY.md.
