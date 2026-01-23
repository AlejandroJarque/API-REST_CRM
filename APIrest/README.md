# RESTful CRM API

RESTful API for a CRM system developed with Laravel.

The project follows strict REST principles, is stateless, uses JSON as the sole data exchange format, and is designed to be secure, versioned, and tested from the start.

## Key Principles
- REST Architecture (resources, HTTP verbs, correct semantics)
- Stateless API (no sessions)
- JSON-only (requests and responses)
- URL versioning (`/api/v1`)
- Security based on OAuth2 (Laravel Passport)
- Test-Driven Development (TDD)
- Quality contract defined by a Definition of Done (DoD)

## Project Documentation
The project's global rules and decisions are located in the ``/docs` folder (./docs):

1. REST Principles and Conventions
2. API Versioning
3. Error Handling and Status Codes
4. Security Strategy
5. Testing Strategy (TDD)
6. Definition of Done (DoD)

These decisions are mandatory for every feature in the project.

## Project Status
Project under development.

Features are implemented using `feature/*` branches following Gitflow.