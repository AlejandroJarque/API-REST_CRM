# Testing Strategy (TDD)

## Principles

- Tests are the source of truth for the project.
- No endpoint is considered finished without tests.
- Behavior is defined before implementation.
- Manual testing is not a validation mechanism.

## Testing framework

- The project uses PHPUnit as the only testing framework.
- PHPUnit is natively integrated with Laravel and provides explicit, predictable tests.
- Mixing testing frameworks is not allowed.

## Testing environment

All tests run in a dedicated testing environment:

- APP_ENV=testing
- Isolated database
- Ephemeral cache, session, and queue drivers
- No dependency on external services

The testing environment is:

- Reproducible
- Disposable
- Safe (cannot affect local or development data)

## Running tests

The test suite is executed using:

```bash
php artisan test