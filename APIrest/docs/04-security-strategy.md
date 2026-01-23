# Security Strategy

## Principles
- The entire API will be protected by tokens.

- Sessions are not used.

- Authentication is based on OAuth2 using Laravel Passport.

## Separation of Duties
- Authentication: who you are
- Authorization: what you can do

These responsibilities should not be mixed.

## Initial Roles
- user: standard authenticated user
- admin: global operational access

## Discarded Alternatives
- Laravel Sanctum:

- Does not fully meet the project's OAuth2 requirements.

## Note
At this point, Passport is not installed or configured. Only the architectural decision is being made.