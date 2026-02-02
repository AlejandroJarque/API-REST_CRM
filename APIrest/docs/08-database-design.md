# Database Design — Relational Model

## Objective

To define the relational model that supports the system's domain and REST contracts before implementing migrations or models.

This document establishes tables, relationships, ownership, and integrity rules at a conceptual level, ensuring consistency with authorization and testing.

## Tables and Purpose

### users
Represents authenticated system users.

Responsibilities:
- Authentication
- Client ownership
- Activity authorship

### clients
Represents clients managed within the system.

Responsibilities:
- Contain client contact information
- Serve as the primary context for activities

### activities
Represents actions performed on a client.

Responsibilities:
- Register business events
- Maintain historical traceability
- Associate action, client, and creator user

## Relationships and Cardinalities

### users → clients (ownership)

- A user can have multiple clients.

- Each client belongs to only one user.

Relationship:
- 1 user → N clients

Justification:
This relationship defines the primary ownership of the domain and is the basis for the authorization rules: a user can only access their own clients.

### Clients → Activities

- A client can have many activities.

- Each activity belongs to a single client.

Relationship:
- 1 client → N activities

Justification:
- Activities without a client lose their meaning within the domain. This relationship guarantees traceability and business consistency.

### Users → Activities (Authorship)

- A user can create many activities.

- Each activity is created by a single user.

Relationship:
- 1 user → N activities

Justification:
- Clearly distinguishes between:
- The client on whom the action is performed
- The user who executes the action

This allows for auditing, user-specific metrics, and future business rules.

## Ownership and Authorization

- Primary ownership is established in the users → clients relationship.

- Access to activities is always through the client.

- - A user can only access:

- Their own clients

- The activities associated with those clients

This design allows for the implementation of simple and verifiable authorization policies.

## Integrity Rules (Conceptual)

The model must comply with the following rules:

- A client cannot exist without a user.

- An activity cannot exist without a client.

- An activity cannot exist without a user creator.

These rules must be enforced later at the database, application, or both levels.

## Testing Considerations

The design allows for:

- Simple creation of minimal data:

- user

- client associated with user

- activity associated with client and user
- Isolation of authorization scenarios:

- user A cannot access user B's data
- Avoidance of orphaned entities that complicate test cleanup

This model is compatible with simple and predictable factories and seeders.

## Conscious Decisions

- No additional technical columns are defined at this point.

- No foreign keys or cascades are specified.

- No premature optimizations (indexes, denormalization) are added.

These decisions are postponed until the implementation phase.

## State

This design validates the alignment between:
- Domain
- Persistence
- Authorization
- Testing