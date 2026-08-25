# Validation Guide: Company Module

## Prerequisites

- Petora Backend project setup with database and `Common` module enabled.

## Setup Commands

Run the migrations to create the company tables and seeders for permissions:

```bash
php artisan migrate
php artisan db:seed
```

## Validation Scenarios

### Scenario 1: Admin Create Company

1. Login to the Admin Dashboard as a Super Admin.
2. Navigate to the Companies menu item.
3. Click "Add New Company".
4. Fill in English Title, Arabic Title, Phone, English Address, and Arabic Address.
5. Click Save.
6. Verify the company appears in the Companies list.

### Scenario 2: Toggle Status

1. On the Companies list, click the status toggle for an active company.
2. Verify the status changes to inactive.
3. Check the database to confirm `is_active` is 0.

### Scenario 3: Verify API Output

1. Send a GET request to `/api/company/companies` (if an API endpoint is exposed for client applications).
2. Verify that only companies with `is_active = true` are returned in the JSON payload, structured via the `success()` helper.
