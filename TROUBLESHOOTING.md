# Troubleshooting Guide

Common issues and solutions when using the WhatsApp Cloud API SDK.

## Installation Issues

### "Class not found: Tiagoandrepro\\WhatsAppCloud\\..."

**Problem:** PHP cannot find SDK classes

**Solutions:**

```bash
# Regenerate autoloader
composer dump-autoload

# Clear PHP OPcache if running PHP-FPM
php-fpm restart  # or your FPM service

# Verify installation
composer show -t
```

### "Composer install fails with permission denied"

**Problem:** Vendor binaries lack execute permission

**Solutions:**

```bash
# Fix permissions
chmod +x vendor/bin/*

# Or reinstall
composer install --no-scripts
composer run-script post-install-cmd
```

## HTTP Client Issues

### "Class not found: GuzzleHttp\\Client"

**Problem:** PSR-18 HTTP client not installed

**Solutions:**

```bash
# Install Guzzle (recommended)
composer require guzzlehttp/guzzle nyholm/psr7

# Or install alternative
composer require symfony/http-client nyholm/psr7
composer require slim/psr7 http-interop/http-factory-slim
```

### "cURL error 60: SSL cert bundle expired"

**Problem:** Old or missing SSL certificates

**Solutions:**

```bash
# Update Composer's CA bundle
composer global require --no-scripts --no-plugins cachetool/cachetool

# Or disable cert verification (development only!)
$httpClient = new GuzzleClient([
    'verify' => false,  // ⚠️ NOT for production
]);
```

### "Connection timeout"

**Problem:** HTTP request taking too long

**Solutions:**

```php
// Increase timeout
$httpClient = new GuzzleClient([
    'timeout' => 60,  // Default: 30s
    'connect_timeout' => 15,
]);

$client = WhatsAppClient::fromDefaults(
    token: $token,
    phoneNumberId: $phoneId,
    httpClient: $httpClient
);
```

## Authentication Issues

### "Unauthorized - Invalid access token"

**Problem:** Token is invalid, expired, or lacks permissions

**Solutions:**

1. **Verify token:**
   - Check token format in Meta App Dashboard
   - Verify token hasn't expired
   - Generate new token if needed

2. **Check permissions:**
   ```
   Required scopes:
   - whatsapp_business_messaging
   - business_management
   ```

3. **Use environment variable:**
   ```php
   $token = getenv('WHATSAPP_TOKEN');
   if (!$token) {
       throw new Exception('WHATSAPP_TOKEN not set');
   }
   
   $client = WhatsAppClient::fromDefaults(
       token: $token,
       phoneNumberId: $phoneId
   );
   ```

### "Forbidden - Insufficient permissions"

**Problem:** Token lacks required scope

**Solutions:**

1. Regenerate token with required scopes
2. Check app configuration in Meta Dashboard
3. Verify business account access

## Message Sending Issues

### ValidationException: "Invalid phone number"

**Problem:** Phone number format incorrect

**Solutions:**

```php
// Correct format (E.164)
'+5511987654321'  // ✓ Correct

// Incorrect formats
'11987654321'     // ✗ Missing country code
'+55 119 8765-4321'  // ✗ Has spaces/dashes
'5511987654321'   // ✗ Missing +
```

### ValidationException: "Invalid recipient type"

**Problem:** Invalid message recipient

**Solutions:**

```php
// Must be valid phone number
$client->messages()->sendText(
    to: '+5511987654321',  // ✓ Phone
    text: 'Hello'
);

// Not valid
// to: 'username'  // ✗ No username support
// to: 'group_id'  // ✗ No group support (yet)
```

### RateLimitException: "Rate limit exceeded"

**Problem:** Too many requests in short time

**Solutions:**

```php
use Tiagoandrepro\WhatsAppCloud\Exception\RateLimitException;

try {
    $response = $client->messages()->sendText($to, $text);
} catch (RateLimitException $e) {
    $retryAfter = $e->getRetryAfterSeconds() ?? 60;
    
    // Wait and retry
    sleep($retryAfter);
    $response = $client->messages()->sendText($to, $text);
}
```

Best practices:
- Implement queue/job system
- Space out requests
- Use batch endpoints when available
- Monitor rate limit headers

### ServerException: "WhatsApp server error"

**Problem:** Meta WhatsApp API returned 5xx error

**Solutions:**

```php
use Tiagoandrepro\WhatsAppCloud\Exception\ServerException;

$maxRetries = 3;
for ($i = 0; $i < $maxRetries; $i++) {
    try {
        $response = $client->messages()->sendText($to, $text);
        break;
    } catch (ServerException $e) {
        if ($i === $maxRetries - 1) {
            throw $e;  // Last retry failed
        }
        
        // Wait with exponential backoff
        $wait = pow(2, $i) * 1000;  // 1s, 2s, 4s
        usleep($wait * 1000);
    }
}
```

## Media Issues

### NotFoundException: "Media not found"

**Problem:** Media ID doesn't exist or already expired

**Solutions:**

```php
// Upload fresh media instead
$uploadResponse = $client->media()->upload(
    filePath: '/path/to/image.jpg',
    mimeType: 'image/jpeg'
);

$mediaId = $uploadResponse->getId();

// Use immediately (media expires after 24h)
$client->messages()->sendImage(
    to: $to,
    media: new MediaObjectID($mediaId)
);
```

### File upload fails

**Problem:** File doesn't exist or wrong format

**Solutions:**

```php
// Check file exists
$filePath = '/path/to/file.jpg';
if (!file_exists($filePath)) {
    throw new Exception("File not found: $filePath");
}

// Check MIME type
$mimeType = mime_content_type($filePath);
echo $mimeType;  // Should be image/jpeg, audio/ogg, etc

// Verify file size
$size = filesize($filePath);
if ($size > 100 * 1024 * 1024) {  // 100MB limit
    throw new Exception("File too large");
}

// Upload
$response = $client->media()->upload($filePath, $mimeType);
```

## Template Issues

### NotFoundException: "Template not found"

**Problem:** Template name incorrect or doesn't exist

**Solutions:**

```php
// List available templates first
$templates = $client->templates()->list();

foreach ($templates->getTemplates() as $template) {
    echo $template->getName() . "\n";
}

// Use exact template name
$response = $client->templates()->sendTemplate(
    phoneNumberId: $phoneId,
    to: '+5511987654321',
    templateName: 'hello_world',  // Exact name
    languageCode: 'en_US'
);
```

### ConflictException: "Template already exists"

**Problem:** Trying to create duplicate template

**Solutions:**

```php
// Check if exists first
try {
    $template = $client->templates()->getTemplate('my_template');
    echo "Template exists";
} catch (NotFoundException $e) {
    // Template doesn't exist, safe to create
    $response = $client->templates()->create('my_template', [...]);
}
```

## Webhook Issues

### Webhook verification fails

**Problem:** Webhook URL not responding correctly

**Solutions:**

```php
<?php

use Tiagoandrepro\WhatsAppCloud\Endpoint\Webhooks;

$webhooks = new Webhooks();
$verifyToken = getenv('WEBHOOK_VERIFY_TOKEN');

// Meta will send GET request during setup
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $verified = $webhooks->verify($_GET, $verifyToken);
    
    if ($verified === false) {
        http_response_code(403);
        exit("Verification failed");
    }
    
    echo $verified;
    exit;
}

// Also handle POST for incoming messages
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ...
}
```

### Missing webhook notifications

**Problem:** Not receiving message/status webhooks

**Solutions:**

1. **Verify webhook registered:**
   - Go to Meta App Dashboard → WhatsApp → Configuration
   - Check webhook URL is correct
   - Verify token matches your code

2. **Check permissions:**
   - Permission: `messages`
   - Permission: `message_status`

3. **Test webhook:**
   ```bash
   curl -X POST https://yoururl.com/webhook \
     -H "Content-Type: application/json" \
     -d '{"object":"whatsapp_business_account"}'
   ```

4. **Monitor logs:**
   ```php
   $logger = new Logger('webhooks');
   $logger->pushHandler(new StreamHandler('logs/webhook.log'));
   
   error_log(json_encode($_POST), 3, 'logs/raw-webhook.log');
   ```

## Performance Issues

### Slow message sending

**Problem:** Messages take long to send

**Solutions:**

```php
// Use queue system (Laravel example)
class SendWhatsAppMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function handle(WhatsAppClient $client)
    {
        try {
            $client->messages()->sendText(...);
        } catch (Exception $e) {
            $this->release(60);  // Retry after 60s
        }
    }
}

// Dispatch async
SendWhatsAppMessage::dispatch($to, $text);
```

### High memory usage

**Problem:** SDK consuming too much memory

**Solutions:**

```php
// Create new client for each request (don't reuse)
$client = WhatsAppClient::fromDefaults(token: $token, phoneNumberId: $id);

// Use streaming for large media
// Process in batches
foreach (array_chunk($phones, 100) as $batch) {
    foreach ($batch as $phone) {
        $client->messages()->sendText($phone, $text);
        gc_collect_cycles();  // Force garbage collection
    }
}
```

## Debug Mode

### Enable detailed logging

```php
use Monolog\Logger;
use Monolog\Handlers\StreamHandler;

$logger = new Logger('whatsapp');
$logger->pushHandler(
    new StreamHandler('logs/debug.log', Logger::DEBUG)
);

$client = WhatsAppClient::fromDefaults(
    token: $token,
    phoneNumberId: $phoneId,
    logger: $logger
);

// All requests/responses logged (sensitive data redacted)
```

### Check response object

```php
try {
    $response = $client->messages()->sendText($to, $text);
    
    // Inspect response
    var_dump($response->getMessageId());
    var_dump($response->toArray());
    
} catch (Exception $e) {
    // Get request ID for debugging with Meta support
    echo "Request ID: " . $e->getRequestId();
}
```

## Getting Help

Still stuck? 

1. **Check documentation:** [docs/](docs/)
2. **Read error details:** Check error message and `RequestId`
3. **Enable logging:** See "Debug Mode" section above
4. **Search issues:** [GitHub Issues](https://github.com/tiagoandrepro/whatsapp-cloud-sdk-php/issues)
5. **Ask question:** [GitHub Discussions](https://github.com/tiagoandrepro/whatsapp-cloud-sdk-php/discussions)
6. **Meta support:** Include request ID when contacting Meta support

## Additional Resources

- [API Status Dashboard](https://status.wouldbe.co/)
- [WhatsApp Cloud API Docs](https://developers.facebook.com/docs/whatsapp/cloud-api)
- [Graph API Errors](https://developers.facebook.com/docs/graph-api/using-graph-api/error-handling)
- [Meta Developers](https://developers.facebook.com/)
