# Clients API Contract

## Resource Description

The clients resource represents a collection of clients managed by the system.
Each client belongs to a user, unless accessed by an admin with global privileges.


## Endpoints

### GET /api/v1/clients

**Purpose**  
List all clients accessible to the authenticated user.

**Authorization**
- user: only own clients
- admin: all clients

**Status Codes**
- 200 OK
- 401 Unauthorized
- 403 Forbidden


### POST /api/v1/clients

**Purpose**  
Create a new client.

**Authorization**
- user
- admin

**Status Codes**
- 201 Created
- 401 Unauthorized
- 422 Unprocessable Entity


### GET /api/v1/clients/{clientId}

**Purpose**  
Retrieve a single client by its identifier.

**Authorization**
- user: only if the client is owned by the user
- admin: any client

**Status Codes**
- 200 OK
- 401 Unauthorized
- 403 Forbidden
- 404 Not Found


### PATCH /api/v1/clients/{clientId}

**Purpose**  
Update an existing client.

**Authorization**
- user: only if the client is owned by the user
- admin: any client

**Status Codes**
- 200 OK
- 401 Unauthorized
- 403 Forbidden
- 404 Not Found
- 422 Unprocessable Entity


### DELETE /api/v1/clients/{clientId}

**Purpose**  
Delete an existing client.

**Authorization**
- user: only if the client is owned by the user
- admin: any client

**Status Codes**
- 204 No Content
- 401 Unauthorized
- 403 Forbidden
- 404 Not Found


## Request Payloads and Validation

### Create Client (POST)

Accepted fields:

- `name` (required, string)
- `email` (required, valid email)
- `phone` (optional, string)
- `address` (optional, string)

Notes:
- `user_id` is NOT accepted from the request body.
- Ownership is always assigned from the authenticated user.

Validation Errors:
- Invalid payload returns **422 Unprocessable Entity**.

### Update Client (PATCH)

Partial updates are supported.

Accepted fields (all optional):

- `name` (string)
- `email` (valid email)
- `phone` (string)
- `address` (string)

Validation Errors:
- Invalid fields return **422 Unprocessable Entity**.


## Error Handling Strategy

The following rules apply consistently to all client endpoints:

- **401 Unauthorized**
  - Request without a valid access token.

- **403 Forbidden**
  - Authenticated user attempting to access a client they do not own.
  - Applies even if the resource exists.

- **404 Not Found**
  - Client does not exist.
  - Handled automatically by route model binding.

- **422 Unprocessable Entity**
  - Validation errors.
  - Takes precedence over authorization errors for authenticated users.

cat >> docs/api/clients.md <<'MD'


## Testing as Contract

The behavior described in this document is enforced by automated feature tests.

Tests cover:

- Authentication requirements
- Ownership and role-based authorization
- Correct HTTP status codes
- Validation rules
- Database side effects

No endpoint is considered valid unless all related tests are passing.


