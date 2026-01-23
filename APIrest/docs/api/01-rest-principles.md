# REST Principles and API Conventions

This API strictly follows REST principles. Deviations without explicit justification are not permitted.

## Mandatory Rules
- The API is stateless (sessions are not used).

- JSON is the only accepted format for requests and responses.

- Resources are named in the plural.

- Examples: `/clients`, `/activities`, `/users`
- Correct use of HTTP verbs:

- GET: retrieve resources

- POST: create resources

- PATCH: partially update

- DELETE: delete resources

## Design Conventions
- Each endpoint represents a resource, not an action.

- RPC-style endpoints (`/doSomething`, `/createClientNow`) are not allowed.

## Justification
These rules ensure:
- Consistency across endpoints
- Ease of testing
- Better documentation (OpenAPI, Postman)
- Better integration with frontends and third parties