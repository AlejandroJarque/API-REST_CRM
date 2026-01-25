## Postman Usage

Postman is used in this project only as a manual inspection and debugging tool.

All API behavior is defined and validated by automated feature tests.
Postman must never be used as the primary validation mechanism.

### When to use Postman

- Inspect real JSON responses
- Debug frontend integration issues
- Manually test access tokens
- Demonstrate API behavior

### When NOT to use Postman

- To validate business rules
- To test authorization logic
- To replace automated tests
- To define API behavior

### Setup

1. Create a Postman environment with:
   - `api_url`
   - `token`
2. Authenticate using the `/api/v1/login` endpoint
3. Use the returned token for authenticated requests

Postman collections are expected to reflect the documented API contract,
not define it.
