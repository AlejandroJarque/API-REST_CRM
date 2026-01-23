# Users API Contract

## Resource Description

The users resource represents system users.
The API clearly distinguishes between self-service operations and administrative operations.


## Endpoints

### GET /api/v1/users/me

**Purpose**  
Retrieve the profile of the authenticated user.

**Authorization**
- user
- admin

**Status Codes**
- 200 OK
- 401 Unauthorized


### PUT /api/v1/users/me

**Purpose**  
Update the profile of the authenticated user.

**Authorization**
- user
- admin

**Status Codes**
- 200 OK
- 401 Unauthorized
- 422 Unprocessable Entity


### GET /api/v1/users

**Purpose**  
List all users in the system.

**Authorization**
- admin only

**Status Codes**
- 200 OK
- 401 Unauthorized
- 403 Forbidden
