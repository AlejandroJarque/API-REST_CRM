## REST Resources

### User
Represents an authenticable identity within the system.

It does not represent a client or a CRM person, but rather the actor operating the system.

### Client
Represents an entity managed by a user within the CRM.

A client always exists within the context of a user.

### Activity
Represents an action or interaction performed on a client (calls, meetings, notes, etc.).

## Responsibilities by Resource

- User

    - Authentication

    - Role (user/admin)

    - Ownership of clients and activities

- Client

    - Client data

    - Direct relationship with a user

- Activity

    - Historical record of actions

    - Always associated with a client

    - Created by a user

## Domain Relationships

- A user owns many clients
- A client belongs to a user
- A client has many activities
- An activity belongs to a client
- An activity is created by a user

## Access Rules by Role

### User
- Can only access their own clients
- Can only access their clients' activities
- Cannot view or modify other users' data

### Admin
- Global access to all resources
- Can audit and manage any entity

## Business Logic (Beyond CRUD)

- An activity cannot exist without a valid user client.
- Statistics per client:

- Total number of activities

- Last recorded activity