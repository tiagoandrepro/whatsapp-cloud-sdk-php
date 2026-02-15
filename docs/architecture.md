# Architecture

This document captures design decisions, trade-offs, and the public API surface.

## Goals

- **Strict typing**: Declare strict types, readonly classes, full type hints
- **Immutable DTOs**: Readonly classes, no setters, safe to pass around
- **PSR-first transport**: PSR-18 HTTP Client, PSR-17 factories, PSR-3 logging
- **Secure by default**: Automatic redaction, input validation, HTTPS enforcement
- **Production-grade**: Retry policy, error mapping, comprehensive testing
- **Framework-agnostic**: No dependencies on monolithic frameworks
- **Extensible**: Public interfaces for custom implementations

## Core Components

### WhatsAppClient
Entry point for the SDK. Lazy-loads all sub-endpoints. Singleton pattern ensures:
- Avoid instantiating unused endpoints
- Simplify client configuration
- Maintain consistent state

**Sub-clients**: messages, media, templates, webhooks, registration, phoneNumbers, webhookSubscriptions, businessProfiles, resumableUploads, wabas, commerceSettings, qrCodes, analytics, flows, billing, blockUsers, businessPortfolio

### Psr18Transport
HTTP abstraction layer. Handles:
1. Build requests (headers, body, authentication)
2. Execute HTTP calls via PSR-18 client
3. Handle retries (429, 5xx only) with exponential backoff + jitter
4. Decode responses via JsonSerializer
5. Map errors to typed exceptions
6. Log sanitized request/response info

### ErrorMapper
Maps HTTP status codes + response bodies to typed exceptions:
- 400/422 → ValidationException
- 401/403 → AuthException
- 404 → NotFoundException
- 409 → ConflictException
- 429 → RateLimitException (respects Retry-After)
- 5xx → ServerException

### JsonSerializer
Safe JSON encode/decode with error handling. Explicit error handling (no silent failures).

### SafeLogger
Structured logging with automatic sensitive data redaction:
- Full phone numbers → `+XXX****XXXX`
- Tokens → `[REDACTED]`
- Authorization headers → `[REDACTED]`
- Configurable field list

### RetryPolicy
Configurable retry behavior for transient failures:
- Only retry on 429 and 5xx
- Exponential backoff: `delay = base * (multiplier ^ attempt)`
- Jitter: `delay + random(0, jitter_ms)`
- Default: 3 retries, 1000ms base, 2x multiplier

## Design Decisions

| Decision | Rationale |
|----------|-----------|
| Typed Exceptions | Better error handling and IDE support |
| Immutable DTOs | Thread-safe, harder to misuse |
| Strict Validation | Fail fast on obvious user errors, let API validate business rules |
| Retry only 429/5xx | 4xx indicate user errors, not transient issues |
| Local then Remote | Quick fail on obvious errors, comprehensive API validation |
| No external dependencies | Callers provide PSR-18, PSR-17, PSR-3 implementations |
| Singleton endpoints | Single client config, lazy-loaded access |

## Error Handling Strategy

```
User Call
    ↓
Endpoint validation (InvalidArgumentException)
    ↓
Transport HTTP request
    ↓
HTTP 2xx → Decode response → DTO
HTTP 429/5xx → Retry (or TransportException if max retries)
HTTP 4xx → ErrorMapper → Typed exception
```

Exceptions carry context: status code, method/path, request ID, Retry-After, sanitized message.

## Testing Strategy

- Mock PSR-18 client (no network calls)
- Fixture-based (real Postman responses)
- PHPUnit with 44 tests, 116 assertions
- No live API tests (avoids flaky tests, rate limiting, data pollution)

## Extensibility

- **Custom Auth**: Implement TokenProviderInterface
- **Custom Logger**: Use any PSR-3 logger
- **Custom HTTP Client**: Use any PSR-18 client
