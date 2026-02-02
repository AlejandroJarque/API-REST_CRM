# API Error Handling Strategy

## Overview

This document defines the global error handling strategy for the API.
All endpoints must comply with the rules described here.

The goal is to provide a consistent and predictable error model for all API consumers.

## Authentication Errors

### 401 Unauthorized

Returned when the request lacks valid authentication credentials.

Typical scenarios:
- Missing authentication token
- Invalid or expired authentication token

The client is expected to authenticate before retrying the request.


## Authorization Errors

### 403 Forbidden

Returned when the user is authenticated but does not have permission to perform the requested action.

Typical scenarios:
- User attempts to access a resource they do not own
- User role does not allow the operation


## Resource Errors

### 404 Not Found

Returned when the requested resource does not exist or is not accessible to the user.

This includes cases where the resource exists but the user is not authorized to know of its existence.

This behavior prevents information leakage.


## Validation Errors

### 422 Unprocessable Entity

Returned when the request payload is syntactically correct but fails domain validation rules.

Typical scenarios:
- Missing required fields
- Invalid field formats
- Business rule violations


## Successful Operations

The API uses the following status codes for successful operations:

- 200 OK: Successful read or update
- 201 Created: Resource successfully created
- 204 No Content: Resource successfully deleted


## Consistency Rules

- All endpoints must use only the status codes defined in this document
- No endpoint may introduce custom or undocumented error responses
- Error responses must follow a consistent structure (to be defined during implementation)
