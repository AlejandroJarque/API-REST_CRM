# Architecture Refactor (no functional changes)

## Objective
Separate business logic from controllers to improve maintainability and prepare for scalability.

## Decisions
- Controllers: HTTP adapters (request → authorize → service → response)
- Services: business rules and minimal orchestration
- Events/Listeners are not implemented yet; only hooks (`onClientCreated`, `onActivityCreated`)
- No changes to routes, status codes, or response formats

## Added Structure
- app/Application/Clients/ClientService.php
- app/Application/Activities/ActivityService.php

## Source of Truth
- `php artisan test` (Feature + Authorization) must remain green.
