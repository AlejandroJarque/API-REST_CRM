# Data Strategy: Factories & Seeders

## Purpose

This project uses a strict separation between factories and seeders to ensure:

- Reliable and deterministic tests
- Fast test execution
- Clean separation between test data and development/demo data

---

## Factories

Factories are designed as **scenario builders**, not random data generators.

They are used to:

- Create explicit ownership scenarios
- Support authorization tests
- Build complex relationships in a controlled way

### Rules

- Factories are used by tests
- Tests must create all required data explicitly
- Factories must not contain business logic
- Factories must not depend on seeders

### Available factories

- UserFactory
- ClientFactory (explicit user ownership)
- ActivityFactory (explicit user + client relations)

---

## Seeders

Seeders are intended **only for development and demo environments**.

They are used to:

- Populate local environments
- Facilitate manual API exploration
- Prepare data for Postman usage

### Rules

- Seeders are never used in tests
- Seeders may use factories internally
- Seeder data must be reproducible and predictable

### Available seeders

- DevelopmentUsersSeeder
- DevelopmentClientsSeeder
- DevelopmentActivitiesSeeder

They are orchestrated by `DatabaseSeeder`.

---

## Running seeders (development only)

```bash
php artisan migrate:fresh --seed
