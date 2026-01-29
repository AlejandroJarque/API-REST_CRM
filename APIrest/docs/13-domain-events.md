

# Domain Events (internal)

# Objective
 Decouple side effects from the main domain flow without changing the HTTP contract.

# Defined events
- ClientCreated: emitted after persisting a valid Client.
- ActivityRegistered: emitted after persisting a valid Activity.

# Rules
 - Events are emitted after success (never before persistence).
 - Listeners do not affect the HTTP response.
 - Errors in listeners do not break the request
   (emission is wrapped in try/catch and reported).

# Current listeners
 - LogClientCreation: writes an internal log.
 - LogActivityRegistered: writes an internal log.

# Future extensions
 Mailing, persistent auditing, webhooks, integrations;
 added as additional listeners.
