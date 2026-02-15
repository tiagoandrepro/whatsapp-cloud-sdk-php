# Security Policy

## Sensitive Data Handling

The SDK implements secure-by-default patterns to protect sensitive information:

### 🔒 Data Never Logged
- Access tokens / Bearer credentials
- Authorization headers
- Full phone numbers (redacted to `+XXX****XXXX`)
- PIN codes or verification codes
- Credit card or payment information
- Personally identifiable information (PII)

### 🛡️ Automatic Redaction
The `SafeLogger` utility automatically redacts sensitive fields before writing to logs:

```php
// Sensitive fields are automatically hidden
$logger->debug('Request sent', [
    'phone' => '+55112345678',  // Logged as: +XXX****5678
    'token' => 'abc123xyz',      // Logged as: [REDACTED]
]);
```

### 🔐 Input Validation

All user inputs are validated locally before HTTP requests:

- **Phone Numbers**: Must be valid E.164 format (validated with regex)
- **Tokens**: Must be non-empty strings
- **URLs**: Must match allowlist (configurable, defaults to `graph.facebook.com`)
- **File Paths**: File existence is checked before upload
- **Categories**: Arrays cannot be empty

### 🚫 Base URL Allowlist

Prevent SSRF attacks by restricting allowed base URLs:

```php
$client = WhatsAppClient::fromDefaults(
    token: '<token>',
    phoneNumberId: '<id>',
    baseUrl: 'https://graph.facebook.com',  // Must match allowlist
    allowedHosts: ['graph.facebook.com', 'custom.domain.com']  // Optional
);
```

Default allowlist: `['graph.facebook.com']`

### 📋 Exception Messages

Exception messages are sanitized and do NOT contain:
- Token fragments
- Full sensitive field values
- System paths or internal details

```php
try {
    $response = $client->messages()->sendText('+1234', 'Hello');
} catch (RateLimitException $e) {
    // Message is safe: "Rate limited. Retry after 120 seconds."
    // Does NOT include: token, headers, full payload
    echo $e->getMessage();
}
```

### 🔄 Retry Policy

Failed requests are retried only for safe HTTP status codes:
- **429** (Rate Limit) - Respects `Retry-After` header
- **5xx** (Server Error) - With exponential backoff + jitter

Failed requests for:
- **4xx** (Client Error) - NOT retried, exception raised immediately
- **401/403** (Auth Errors) - NOT retried

Maximum retries: 3 (configurable)

### 🔌 HTTPS Only

The SDK enforces HTTPS in production. HTTP is only allowed for testing with custom base URLs.

### 🛑 Transport Security

The SDK uses PSR-18 HTTP Client abstraction. Ensure your PSR-18 implementation is configured for:
- Certificate validation enabled
- TLS 1.2+ only
- Secure cipher suites

Example with Guzzle:

```php
use GuzzleHttp\Client as GuzzleClient;

$httpClient = new GuzzleClient([
    'verify' => true,  // Verify SSL certificates
    'timeout' => 30,   // Total timeout
]);

$client = WhatsAppClient::fromDefaults(
    token: '<token>',
    phoneNumberId: '<id>',
    httpClient: $httpClient
);
```

### 🔔 Reporting Security Issues

If you discover a security vulnerability, **please do NOT open a public issue**. Instead:

1. Email: security@whatsapp-cloud-sdk.example.com (replace with actual email)
2. Include:
   - Description of the vulnerability
   - Steps to reproduce
   - Potential impact
   - Suggested fix (if you have one)

3. Allow 90 days for a patch before public disclosure

We take security seriously and will respond promptly to responsible disclosures.

### 📚 References

- [OWASP: Sensitive Data Exposure](https://owasp.org/www-project-top-ten/)
- [CWE-532: Insertion of Sensitive Information into Log File](https://cwe.mitre.org/data/definitions/532.html)
- [Meta Security Best Practices](https://developers.facebook.com/docs/security)
