# Postman Collection – API v1

## Purpose

This document describes the **Postman collection** created for **API v1**. The collection is intended to be an **operational tool**, not a source of truth for automated testing.

It is designed to support:

* Quick sanity-checks after backend changes
* Manual exploration and debugging
* Frontend and QA validation
* Stakeholder demos

Automated tests remain the authoritative verification mechanism.

## Scope

The collection covers **API v1** and includes:

* Authentication
* Clients resource
* Activities resource
* Explicit error scenarios

The collection is versioned and maintained in the repository.

## Git & Versioning

* Branch used: `feature/postman-collection`
* Base branch: `develop`
* The Postman collection is treated as a **versioned deliverable**
* Changes are reviewed via Pull Request

## Collection Structure

API v1
├── Auth
├── Clients
├── Activities
└── Errors

### Rationale

* **API v1** as root aligns with URL versioning and future v2 expansion
* **Auth** is separated because it is transversal
* **Clients** and **Activities** map directly to REST resources
* **Errors** contains reproducible, intentional error cases

## Environments & Variables

### Environment: `API-REST_CRM (local)`

The collection relies on environment variables to ensure reproducibility and avoid hardcoding.

| Variable      | Description                             |
| ------------- | --------------------------------------- |
| `base_url`    | API host (e.g. `http://localhost:8000`) |
| `token`       | Bearer access token obtained via login  |
| `client_id`   | Reusable client identifier              |
| `activity_id` | Reusable activity identifier            |

### Principles

* No hardcoded hosts
* No hardcoded tokens
* IDs are stored as variables and reused

## Authentication (Auth)

### Login

* **Endpoint**: `POST /api/v1/login`
* **Purpose**: Obtain a Bearer access token

#### Expected Result

* `200 OK`
* Response contains `access_token`

The token is manually stored in the environment variable `token`.

## Clients

### List Clients

* **Endpoint**: `GET /api/v1/clients`
* **Auth required**: Yes

#### Behavior

* `200 OK` when clients are visible
* `404 Not Found` when the authenticated user has no accessible clients

This behavior is **intentional** and part of the API contract.

### Create Client

* **Endpoint**: `POST /api/v1/clients`

#### Required Fields

```json
{
  "name": "Client name",
  "email": "client@example.com"
}
```

#### Expected Result

* `201 Created`
* Response includes the new `id`

The returned `id` is stored in `client_id`.

### View Client

* **Endpoint**: `GET /api/v1/clients/{client_id}`

#### Expected Result

* `200 OK` if the client exists and belongs to the authenticated user
* `404 Not Found` otherwise

### Update Client

* **Endpoint**: `PATCH /api/v1/clients/{client_id}`

#### Example Body

```json
{
  "name": "Updated client name"
}
```

#### Expected Result

* `200 OK`

### Delete Client

* **Endpoint**: `DELETE /api/v1/clients/{client_id}`

#### Expected Results

* `204 No Content` or `200 OK` when deletion succeeds
* `404 Not Found` if the client does not exist or is not accessible

Both outcomes are valid.


## Activities

### Create Activity

* **Endpoint**: `POST /api/v1/activities`

#### Required Fields

```json
/*{
  "title": "Activity title",
  "description": "Activity description",
  "client_id": {{client_id}}
}*/
```

#### Expected Result

* `201 Created`
* Response includes `id`

The returned `id` is stored in `activity_id`.

### View Activity

* **Endpoint**: `GET /api/v1/activities/{activity_id}`

#### Expected Result

* `200 OK` if accessible
* `404 Not Found` otherwise

### Update Activity

* **Endpoint**: `PATCH /api/v1/activities/{activity_id}`

#### Example Body

```json
{
  "title": "Updated activity",
  "description": "Updated description"
}
```

#### Expected Result

* `200 OK`

### Delete Activity

* **Endpoint**: `DELETE /api/v1/activities/{activity_id}`

#### Expected Results

* `204 No Content`, `200 OK`, or `404 Not Found`

All are acceptable depending on resource state.

## Errors

The **Errors** folder documents intentional, reproducible error scenarios.

### 401 Unauthorized – No Token

* **Endpoint**: `GET /api/v1/clients`
* **Auth**: No Authorization header

#### Expected Result

```
401 Unauthorized
{
  "message": "Unauthenticated."
}
```

### 422 Validation Error – Missing Email

* **Endpoint**: `POST /api/v1/clients`

```json
{
  "name": "Invalid client"
}
```

#### Expected Result

* `422 Unprocessable Content`
* Validation error details

### 404 Not Found – Client Does Not Exist

* **Endpoint**: `GET /api/v1/clients/999999`

#### Expected Result

* `404 Not Found`

### 422 Validation Error – Invalid Client on Activity

* **Endpoint**: `POST /api/v1/activities`

```json
{
  "title": "Invalid activity",
  "description": "Invalid client",
  "client_id": 999999
}
```

#### Expected Result

* `422 Unprocessable Content`

## Design Notes

* Postman is an **operational aid**, not a testing framework
* Error responses are treated as **first-class API contracts**
* 404 responses are intentionally used to avoid resource disclosure
* All behavior is aligned with backend tests (source of truth)

## Definition of Done

* Collection is versioned
* Environments use variables only
* Auth, Clients, Activities, and Errors are covered
* Requests are reproducible and documented
* Collection can be used as a sanity-check

## Final Notes

This Postman collection reflects the **actual runtime behavior** of the API. It intentionally avoids speculative endpoints or artificial success paths, prioritizing correctness, clarity, and reproducibility.