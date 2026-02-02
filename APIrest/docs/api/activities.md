# Activities API Contract

## Resource Description

The activities resource represents actions or events performed in the context of a client.
Each activity belongs to a client, and indirectly to a user through that client.

Activities are exposed as a top-level resource.
Ownership and access control are enforced via authorization rules, not URL nesting.


## Endpoints

### GET /api/v1/activities

**Purpose**  
List all activities accessible to the authenticated user.

**Authorization**
- user: only activities belonging to the user’s own clients
- admin: all activities

**Status Codes**
- 200 OK
- 401 Unauthorized


### POST /api/v1/activities

**Purpose**  
Create a new activity.

**Authorization**
- user: only for clients owned by the user
- admin: for any client

**Notes**
- `client_id` is required
- `user_id` is derived from the client and cannot be provided by the request

**Status Codes**
- 201 Created
- 401 Unauthorized
- 403 Forbidden
- 422 Unprocessable Entity


### GET /api/v1/activities/{activityId}

**Purpose**  
Retrieve a single activity by its identifier.

**Authorization**
- user: only if the activity belongs to one of the user’s clients
- admin: any activity

**Status Codes**
- 200 OK
- 401 Unauthorized
- 403 Forbidden
- 404 Not Found


### PATCH /api/v1/activities/{activityId}

**Purpose**  
Partially update an existing activity.

**Authorization**
- user: only if the activity belongs to one of the user’s clients
- admin: any activity

**Notes**
- Partial updates only (`PATCH`)
- Validation rules use `sometimes`
- Only validated fields are updated
- `client_id` and `user_id` cannot be modified

**Status Codes**
- 200 OK
- 401 Unauthorized
- 403 Forbidden
- 404 Not Found
- 422 Unprocessable Entity


### DELETE /api/v1/activities/{activityId}

**Purpose**  
Delete an existing activity.

**Authorization**
- user: only if the activity belongs to one of the user’s clients
- admin: any activity

**Status Codes**
- 204 No Content
- 401 Unauthorized
- 403 Forbidden
- 404 Not Found

