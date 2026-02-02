# Dashboard API

## Overview

The Dashboard API provides an aggregated, read-only view of key metrics and alerts for the authenticated user. It is designed to be lightweight, fast, and aligned with existing authorization rules. The dashboard does not represent a persistent entity and has no CRUD operations.

## Purpose

* Provide a single endpoint for frontend dashboards
* Reduce round-trips by aggregating counters and alerts
* Respect ownership and role-based access (user vs admin)
* Avoid introducing new domain entities or persistence

## Endpoint

```
GET /api/v1/dashboard
```

## Authentication

* Required: Bearer token (OAuth2 via Passport)
* Unauthorized requests return `401`

## Authorization Rules

### User

* `clients_count`: number of clients owned by the user
* `activities_count`: number of activities associated with the user's clients
* `pending_activities_alerts`: pending activities associated with the user's clients

### Admin

* `clients_count`: total number of clients
* `activities_count`: total number of activities
* `pending_activities_alerts`: all pending activities

## Definition of "Pending Activity"

An activity is considered **pending** when:

* `completed_at` is `NULL`

This rule is explicit, testable, and intentionally simple. Additional alert types may be introduced in the future.

## Response Format

```json
{
  "clients_count": 5,
  "activities_count": 12,
  "pending_activities_alerts": [
    {
      "activity_id": 42
    }
  ]
}
```

### Fields

* `clients_count` *(integer)*: Number of visible clients
* `activities_count` *(integer)*: Number of visible activities
* `pending_activities_alerts` *(array)*: List of pending activity alerts

Each alert currently contains:

* `activity_id`: Identifier of the pending activity

## Status Codes

* `200 OK`: Dashboard loaded successfully
* `401 Unauthorized`: Missing or invalid authentication token

## Design Notes

* Read-only endpoint
* No pagination (expected low alert volume)
* No caching (premature at this stage)
* No real-time updates or events
* No per-widget endpoints

## Testing

The dashboard behavior is fully defined by feature tests covering:

* Authentication (401 without token)
* User vs admin visibility
* Correct counters per role
* Explicit pending activity rule

## Evolution

This endpoint is intentionally designed to grow by adding new widgets or fields to the response without breaking existing consumers.
