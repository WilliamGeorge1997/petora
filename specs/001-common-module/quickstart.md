# Quickstart Validation: Common Module

## Setup

1. Add the seeder to `database/seeders/DatabaseSeeder.php`: `$this->call([\Modules\Common\Database\Seeders\CommonDatabaseSeeder::class]);`
2. Run migrations: `php artisan migrate`
3. Run seeder: `php artisan db:seed`

## Validation Scenarios

### 1. View Settings (Admin)
- Authenticate as Super Admin
- Visit `/admin/common/settings`
- Expected: Settings UI loads successfully with seeded data (tax, terms, etc.).

### 2. Update Settings
- POST to `/admin/common/settings` with an updated value for a setting
- Expected: Setting updates in the database and reflects on the UI.

### 3. Retrieve Static Info (API)
- GET `/api/common/terms` with header `Accept-Language: ar`
- Expected: JSON response with Arabic terms.
- GET `/api/common/tax`
- Expected: JSON response with the global tax value.

### 4. Layout Extension Test
- Create a test view in another module (or scratchpad).
- Use `@extends('common::layouts.master')`
- Expected: The view loads the standard admin sidebar, navbar, and includes Bootstrap 5 assets successfully without errors.
