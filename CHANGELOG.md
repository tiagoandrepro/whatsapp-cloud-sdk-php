# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-02-15

### Added

#### Core
- PSR-18 HTTP Client transport with retry policy (429/5xx only)
- Secure JSON serializer with error handling
- Error mapper with typed exceptions (ValidationException, AuthException, NotFoundException, ConflictException, RateLimitException, ServerException)
- Safe logger with automatic sensitive data redaction (tokens, PII)
- Configurable retry policy with backoff + jitter
- Base URL allowlist for SSRF prevention

#### Endpoints
- MessagesEndpoint - 8 methods (text, template, image, document, video, audio, media, read status)
- MediaEndpoint - 2 methods (upload, download)
- TemplatesEndpoint - 3 methods (create, update, delete)
- Webhooks - Parse and validate webhook payloads
- RegistrationEndpoint - 2 methods (register, deregister phone)
- PhoneNumbersEndpoint - 2 methods (get, list)
- WebhookSubscriptionsEndpoint - 3 methods (subscribe, list, unsubscribe)
- BusinessProfilesEndpoint - 2 methods (get, update)
- ResumableUploadEndpoint - 3 methods (create session, upload, query status)
- WabaEndpoint - 1 method (get WABA info)
- CommerceSettingsEndpoint - 2 methods (get, update)
- QrCodesEndpoint - 5 methods (get, list, create, update, delete)
- AnalyticsEndpoint - 2 methods (get analytics, get conversation analytics)
- FlowsEndpoint - 14 methods (create, migrate, get, list, publish, update, upload JSON, encryption, metrics)
- BillingEndpoint - 1 method (get credit lines)
- BlockUsersEndpoint - 3 methods (get, block, unblock users)
- BusinessPortfolioEndpoint - 1 method (get portfolio)

#### Data Transfer Objects
- 40+ typed, readonly DTOs with validation
- Automatic field mapping from API responses

#### Testing
- PHPUnit test suite with 44 tests, 116 assertions
- Mock PSR-18 client for testing without network
- 100% test coverage of core transport and endpoints

#### Documentation
- Comprehensive README with quickstart
- 18 usage guide documents with code examples
- Complete API map (59 endpoints)
- Architecture documentation with design decisions
- Security policy with best practices
- Error handling guide with exception mapping
- Configuration guide with all options
- Compatibility guide for Graph API versioning

#### CI/CD
- GitHub Actions workflow
- PHPStan static analysis (level: max)
- PHP-CS-Fixer code style enforcement
- Automated test execution

### Security
- Automatic redaction of sensitive data in logs
- Input validation on all user-provided values
- HTTPS enforced in production
- Base URL allowlist to prevent SSRF
- Token and credential isolation

---
