# Compatibility

This document covers SDK compatibility with different environments, PHP versions, and Meta API versions.

## PHP Version Support

| PHP Version | Support | Notes |
|-------------|---------|-------|
| 8.3 | ❌ Not Supported | SDK requires 8.4+ features |
| 8.4+ | ✅ Required | Full support, strict types, readonly classes |

**Recommendation**: Use PHP 8.4.8+ for latest security patches.

## PSR Compliance

The SDK adheres to all relevant PSRs:

| PSR | Purpose | Compliance |
|-----|---------|-----------|
| PSR-1 | Basic Coding Standard | ✅ Full |
| PSR-3 | Logger Interface | ✅ Full |
| PSR-4 | Autoloading | ✅ Full |
| PSR-12 | Extended Coding Style | ✅ Full |
| PSR-17 | HTTP Message Factories | ✅ Required (you provide) |
| PSR-18 | HTTP Client | ✅ Required (you provide) |

### PSR-18 HTTP Clients

Compatible with any PSR-18 compliant HTTP client:
- ✅ Guzzle 7+
- ✅ Symfony HttpClient
- ✅ nyholm/psr7-native
- ✅ ReactPHP (async)
- ✅ Amp (async)

### PSR-3 Loggers

Compatible with any PSR-3 compliant logger:
- ✅ Monolog
- ✅ Laminas Log
- ✅ NullLogger
- ✅ Custom implementations

## Graph API Version Support

Default: `v24.0` (configurable)

| Version | Status | Notes |
|---------|--------|-------|
| v24.0 | ✅ Recommended | Latest, fully tested |
| v23.0 | ✅ Supported | Should work |
| v22.0 | ⚠️ Legacy | May work but deprecated |
| v21.0 | ❌ Unsupported | Meta discontinued |

**Track breaking changes**: Check [Graph API Changelog](https://developers.facebook.com/docs/graph-api/changelog)

## Operating System Support

The SDK is pure PHP and works on any OS that runs PHP 8.4:
- ✅ Linux (recommended for production)
- ✅ macOS
- ✅ Windows
- ✅ Docker containers

## Framework Integration

The SDK works with any PHP framework:
- ✅ Laravel
- ✅ Symfony
- ✅ Slim
- ✅ Plain PHP

## TLS/HTTPS Requirements

The SDK requires secure HTTPS connections in production:
- ✅ TLS 1.2+
- ✅ Valid SSL/TLS certificate
- ✅ Certificate verification enabled

## Backwards Compatibility

The SDK follows [Semantic Versioning](https://semver.org/):
- **MAJOR** (1.0, 2.0): Breaking changes allowed
- **MINOR** (1.1, 1.2): New features, backwards compatible
- **PATCH** (1.0.1): Bug fixes only

**Recommendation**: Specify version constraints:

```json
{
    "require": {
        "whatsapp-cloud/whatsapp-cloud-sdk-php": "^1.0"
    }
}
```
