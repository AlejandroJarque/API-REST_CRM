# API Versioning

## Convention
All API routes must be versioned under the path:

/api/v1/...

Example:

/api/v1/clients

## Justification
- Avoids silent breaking changes.

- Allows for API evolution without breaking existing integrations.

- Almost no initial cost, high long-term value.

## Discarded Alternatives
- Versioning by headers:

- Greater friction on the frontend.

Less visibility in tools like Postman or Swagger.

## Note
Currently, only `v1` exists, but the convention applies from the first endpoint.