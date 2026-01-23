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


### PUT /api/v1/clients/{clientId}

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
