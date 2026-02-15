# Contributing to WhatsApp Cloud API SDK

Thank you for considering contributing to the WhatsApp Cloud API SDK! This document provides guidelines and instructions for contributing.

## Code of Conduct

- Be respectful and inclusive
- Focus on the code, not the person
- Help others learn and grow

## Getting Started

### Prerequisites

- PHP 8.4+
- Composer
- Git

### Setup Development Environment

```bash
# Clone the repository
git clone git@github.com:tiagoandrepro/whatsapp-cloud-sdk-php.git
cd whatsapp-cloud-sdk-php

# Install dependencies
composer install

# Verify setup
composer test
```

## Development Workflow

### 1. Create a Feature Branch

```bash
git checkout -b feature/your-feature-name
```

### 2. Make Changes

- Write clean, well-documented code
- Follow PSR-12 coding standards
- Add tests for new functionality
- Update documentation as needed

### 3. Run Quality Checks

```bash
# Run tests
composer test

# Check code style
./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php --allow-risky=yes

# Run static analysis
composer stan

# Validate composer
composer validate --no-interaction --strict
```

### 4. Commit Changes

```bash
git add .
git commit -m "type: brief description

Longer explanation if needed.
- Bullet point 1
- Bullet point 2"
```

**Commit types:**
- `feat:` - New feature
- `fix:` - Bug fix
- `refactor:` - Code refactoring
- `test:` - Test additions/changes
- `docs:` - Documentation changes
- `chore:` - Build/dependency changes
- `style:` - Code style (spaces, semicolons, etc)
- `perf:` - Performance improvements
- `security:` - Security fixes/improvements

### 5. Push and Create Pull Request

```bash
git push origin feature/your-feature-name
```

Then create a Pull Request on GitHub with:
- Clear title and description
- Reference to related issues
- Screenshots/examples if applicable

## Testing

### Add New Tests

1. Create test in `tests/` directory
2. Follow existing test patterns
3. Test both success and error cases
4. Run tests: `composer test`

### Test Coverage

- Aim for 100% endpoint coverage
- Include integration tests
- Test error scenarios
- Verify with: `composer test`

## Documentation

### Update Docs For:

- New endpoints
- New DTOs
- API changes
- Configuration options
- Security considerations

### Documentation Files:

- `README.md` - Overview and quickstart
- `docs/configuration.md` - Configuration guide
- `docs/usage-*.md` - Usage examples
- `SECURITY.md` - Security policy
- `docs/errors.md` - Error handling
- `docs/architecture.md` - Architecture info
- `docs/compatibility.md` - Compatibility matrix

## Pull Request Process

1. **Update documentation** - Reflect any changes in docs/
2. **Add tests** - Ensure new code has tests
3. **Run checks** - All tests must pass
4. **Code review** - Address feedback promptly
5. **Squash commits** - Clean up history if needed
6. **Merge** - Maintainer will merge when ready

### PR Requirements:

- ✅ All tests passing
- ✅ Code style compliant
- ✅ PhpStan passes
- ✅ Documentation updated
- ✅ Meaningful commit messages

## Reporting Issues

### Bug Reports Include:

- Clear description of the issue
- Steps to reproduce
- Expected vs actual behavior
- PHP version
- SDK version
- Relevant code snippets

### Feature Requests Include:

- Use case and motivation
- Proposed API design
- Implementation approach (if known)
- Examples of similar features

## Release Process

Releases follow [Semantic Versioning](https://semver.org/):

- **MAJOR** (1.0, 2.0) - Breaking changes
- **MINOR** (1.1, 1.2) - New features (backwards compatible)
- **PATCH** (1.0.1) - Bug fixes only

## Questions?

- Open a GitHub Discussion
- Check existing issues first
- Review documentation thoroughly

## Additional Resources

- [PSR Standards](https://www.php-fig.org/)
- [Graph API Docs](https://developers.facebook.com/docs/graph-api)
- [WhatsApp Cloud API](https://developers.facebook.com/docs/whatsapp/cloud-api)
- [Semantic Versioning](https://semver.org/)

Thank you for contributing! 🙏