# Testing Strategy (TDD)

## Principles
- Tests are the source of truth for the project.

- No endpoint is considered finished without tests.

- Behavior is defined before implementation.

## Types of Tests
- Functional tests:

- Required per endpoint.

- Validate status codes, responses, and authorization.

- Unit tests:

- Only when complex business logic exists.

## Rules
- If it's not tested, it doesn't exist.

- Tests must be able to detect regressions.

## Discarded Alternative
- Writing tests at the end:

- Generates technical debt.

- Doesn't validate security or contracts from the beginning.