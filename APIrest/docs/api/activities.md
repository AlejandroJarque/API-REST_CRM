# Activities API Contract

## Resource Description

The activities resource represents actions or events associated with a specific client.
An activity cannot exist independently and is always scoped to a client.

---

## Endpoints

### GET /api/v1/clients/{clientId}/activities

**Purpose**  
List all activities associated with a given client.

**Authorization**
- user: only if the client is owned by the user
- admin: any client

**Status Codes**
- 200 OK
- 401 Unauthorized
- 403 Forbidden
- 404 Not Found

---

### POST /api/v1/clients/{clientId}/activities

**Purpose**  
Create a new activity for a given client.

**Authorization**
- user: only if the client is owned by the user
- admin: any client

**Status Codes**
- 201 Created
- 401 Unauthorized
- 403 Forbidden
- 404 Not Found
- 422 Unprocessable Entity

---

### GET /api/v1/clients/{clientId}/activities/{activityId}

**Purpose**  
Retrieve a single activity by its identifier within the context of a client.

**Authorization**
- user: only if the client is owned by the user
- admin: any client

**Status Codes**
- 200 OK
- 401 Unauthorized
- 403 Forbidden
- 404 Not Found

---

### PUT /api/v1/clients/{clientId}/activities/{activityId}

**Purpose**  
Update an existing activity.

**Authorization**
- user: only if the client is owned by the user
- admin: any client

**Status Codes**
- 200 OK
- 401 Unauthorized
- 403 Forbidden
- 404 Not Found
- 422 Unprocessable Entity

---

### DELETE /api/v1/clients/{clientId}/activities/{activityId}

**Purpose**  
Delete an existing activity.

**Authorization**
- user: only if the client is owned by the user
- admin: any client

**Status Codes**
- 204 No Content
- 401 Unauthorized
- 403 Forb
