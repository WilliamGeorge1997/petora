# Validation Guide: 002-admin-module

This document provides steps to manually validate the Admin Module functionality end-to-end.

## Prerequisites
- The `Common` module must be active and functional.
- The Petora `db:seed` must have run, which executes `AdminDatabaseSeeder`.

## Verification Steps

### 1. Database & Seeder Check
```bash
php artisan tinker
> Modules\Admin\app\Models\Admin::count(); // Should be 1
> Spatie\Permission\Models\Role::count(); // Should be at least 1 (Super Admin)
> Spatie\Permission\Models\Permission::count(); // Should be 46
```

### 2. Login Flow
1. Start the server: `php artisan serve`
2. Navigate to `http://localhost:8000/admin/login`
3. Enter `admin@admin.com` and password `123456789`
4. **Expected**: Redirected to `/admin/dashboard`.

### 3. Profile Update
1. From the dashboard, navigate to Profile Edit (`/admin/edit-profile`).
2. Change your name and optionally upload a test image.
3. Submit the form.
4. **Expected**: Redirected back with a success message; the new name and image appear.

### 4. Admin User Management
1. Navigate to `/admin/admins`.
2. Click "Create Admin", fill out details, and assign the `Super Admin` role.
3. Submit the form.
4. **Expected**: The new admin appears in the table.
5. Click the "Active" toggle button.
6. **Expected**: The badge flips from Active to Inactive.
7. Click "Delete".
8. **Expected**: The admin is removed from the table.

### 5. Role Management
1. Navigate to `/admin/roles`.
2. Observe the right-side form containing the permission matrix grouped by categories (e.g. `Admin`, `Product`).
3. Enter "Test Role" and select a few permissions.
4. Click Save.
5. **Expected**: The role appears in the list.
6. Click "Edit" on the new role.
7. **Expected**: The selected permissions are pre-checked.
8. Click "Delete".
9. **Expected**: The role is removed.
