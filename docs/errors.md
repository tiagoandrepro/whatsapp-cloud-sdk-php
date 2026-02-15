# Error Handling

The SDK provides typed exceptions for precise error handling. All exceptions extend `ApiException`.

## Exception Hierarchy

```
Throwable
  └── Exception
      └── ApiException (base)
          ├── ValidationException (400/422)
          ├── AuthException (401/403)
          ├── NotFoundException (404)
          ├── ConflictException (409)
          ├── RateLimitException (429)
          ├── ServerException (5xx)
          └── TransportException (network/client errors)
```

## HTTP Status Mapping

| HTTP Status | Exception | Cause |
|-------------|-----------|-------|
| 400 / 422 | ValidationException | Invalid input provided to API |
| 401 | AuthException | Invalid or expired token |
| 403 | AuthException | Insufficient permissions |
| 404 | NotFoundException | Resource not found |
| 409 | ConflictException | Resource already exists or state conflict |
| 429 | RateLimitException | Rate limit exceeded |
| 5xx | ServerException | Meta API server error |
| Network error | TransportException | HTTP client error (connection timeout, DNS failure, etc.) |

## Exception Properties

All exceptions expose properties:
- `getCode()` - HTTP status code
- `getMessage()` - Safe, sanitized message
- `getRequestId()` - Request ID from x-fb-request-id header (if available)
- `getRetryAfterSeconds()` - For RateLimitException, seconds until throttle expires

## Exception Handling Patterns

### Rate Limiting (Backoff & Retry)

```php
use WhatsAppCloud\Exception\RateLimitException;

try {
    $response = $client->messages()->sendText($to, $text);
} catch (RateLimitException $e) {
    $retryAfter = $e->getRetryAfterSeconds() ?? 60;
    error_log("Rate limited. Retrying after {$retryAfter}s");
    sleep($retryAfter);
    $response = $client->messages()->sendText($to, $text);
}
```

### Authentication Errors (Refresh Token & Retry)

```php
use WhatsAppCloud\Exception\AuthException;

try {
    $response = $client->messages()->sendText($to, $text);
} catch (AuthException $e) {
    $newToken = refreshAccessToken();
    $client = WhatsAppClient::fromDefaults(
        token: $newToken,
        phoneNumberId: $phoneId
    );
    $response = $client->messages()->sendText($to, $text);
}
```

### Validation Errors (Fail Fast)

```php
use WhatsAppCloud\Exception\ValidationException;

try {
    $response = $client->messages()->sendText($to, $text);
} catch (ValidationException $e) {
    error_log("Validation error: " . $e->getMessage());
    throw new BusinessLogicException($e->getMessage());
}
```

### Not Found (Graceful Degradation)

```php
use WhatsAppCloud\Exception\NotFoundException;

try {
    $template = $client->templates()->getTemplate($templateId);
} catch (NotFoundException $e) {
    $template = $fallbackTemplate;
}
```

### Server Errors (Retry with Backoff)

```php
use WhatsAppCloud\Exception\ServerException;

$maxRetries = 3;
$attempt = 0;

while ($attempt < $maxRetries) {
    try {
        $response = $client->messages()->sendText($to, $text);
        break;
    } catch (ServerException $e) {
        $attempt++;
        if ($attempt >= $maxRetries) throw $e;
        sleep(1 << $attempt);  // Exponential backoff
    }
}
```

### Generic Catch (Log & Alert)

```php
use WhatsAppCloud\Exception\ApiException;

try {
    $response = $client->media()->upload($filePath, $mimeType);
} catch (ApiException $e) {
    error_log("WhatsApp API error: " . $e->getMessage());
    alertOps([
        'status' => $e->getCode(),
        'request_id' => $e->getRequestId(),
    ]);
    throw $e;
}
```

## Best Practices

### ✅ DO

- Catch specific exception types
- Respect RateLimitException::getRetryAfterSeconds()
- Log exceptions (messages are sanitized)
- Fail fast on ValidationException
- Implement exponential backoff for ServerException

### ❌ DON'T

- Catch bare `Exception` (too broad)
- Retry ValidationException (won't help)
- Ignore RateLimitException (can lead to account suspension)
- Log the full exception stack trace in production
- Retry 4xx errors endlessly
