# Error Handling and Status Codes

The API must respond consistently to errors.

## Allowed Status Codes
- 200 OK
- 201 Created
- 400 Bad Request
- 401 Unauthorized
- 403 Forbidden
- 404 Not Found
- 422 Unprocessable Entity (validations)
- 500 Internal Server Error

## Key Rule
Always returning `200` with a `success=false` flag is not allowed.

HTTP semantics must be respected.

## Standard Error Structure
Errors must include:
- A clear message for the frontend
- Useful information for debugging (when applicable)

## Rationale
- Allows the frontend to handle errors deterministically.

- Facilitates automated testing.

- Improves observability and maintainability.