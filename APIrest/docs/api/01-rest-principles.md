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

## API Base Path and Versioning

All endpoints are exposed under the following base path:

/api/v1

Any change that breaks backward compatibility requires a new version.

## Allowed HTTP Status Codes

The API uses a limited and explicit set of HTTP status codes:

- 200 OK: Successful read or update
- 201 Created: Resource successfully created
- 204 No Content: Resource successfully deleted
- 401 Unauthorized: Request lacks valid authentication
- 403 Forbidden: Authenticated but not allowed to perform this action
- 404 Not Found: Resource does not exist or is not accessible
- 422 Unprocessable Entity: Validation errors

No other status codes are allowed unless explicitly justified.

## Authorization Rules

The API distinguishes between two roles:

- user: can only access resources they own
- admin: has global access to all resources

Each endpoint must explicitly state which roles are allowed and how ownership is enforced.

## Contract Authority

All HTTP contracts defined under docs/api must comply with the rules defined in this document.

Any endpoint that violates these principles is considered invalid.