# Quickstart Validation Guide: Admin Module

**Purpose**: Runnable end-to-end validation scenarios to prove the Admin Module works correctly after implementation.
**Date**: 2026-08-24

---

## Prerequisites

- Laravel app is running (`composer run dev` or `php artisan serve`)
- Database is accessible and configured in `.env`
- `php artisan migrate` has been run (includes `admins` table)
- `php artisan db:seed` has been run (seeds Super Admin + permissions + roles)

---

## Scenario 1 — Login Flow

**Goal**: Verify session-based authentication works end-to-end.

```
1. Visit:  GET /admin/login
   Expect: Login form renders (no error, no redirect)

2. Submit: POST /admin/login
   Body:   email=admin@admin.com, password=123456789
   Expect: Redirect to /admin/dashboard (200 OK, dashboard view loads)

3. Submit: POST /admin/login with inactive admin credentials
   Expect: Redirect back to /admin/login with error message (account not active)

4. Submit: POST /admin/login with wrong password
   Expect: Redirect back to /admin/login with credentials error

5. Visit:  GET /admin/dashboard (without being logged in)
   Expect: Redirect to /admin/login
```

---

## Scenario 2 — Admin CRUD

**Goal**: Verify full CRUD + activate for admin accounts.

```
1. Logged in as Super Admin → Visit GET /admin/admins
   Expect: Paginated list loads (Super Admin account NOT shown in list)

2. Visit GET /admin/admins/create
   Expect: Create form loads with role dropdown populated

3. POST /admin/admins (create)
   Body:   name=Test Admin, email=test@test.com, password=12345678,
           password_confirmation=12345678, phone=0501234567, role=Super Admin
   Expect: Redirect back to /admin/admins with new admin visible in list

4. POST /admin/admins/{id}/activate
   Expect: JSON or redirect — is_active flipped; badge changes in UI

5. Visit GET /admin/admins/{id}/edit
   Expect: Edit form loads with current data pre-filled

6. PUT /admin/admins/{id} (update without new password)
   Body:   name=Updated Name, email=test@test.com, phone=0509999999, role=Super Admin
   Expect: Admin updated; password unchanged in DB

7. DELETE /admin/admins/{id}
   Expect: Admin removed from list; image file deleted from public/uploads/admin/

8. Attempt DELETE /admin/admins/{id} while logged in as non-Super Admin without Delete-admin permission
   Expect: 403 Forbidden
```

---

## Scenario 3 — Role Management

**Goal**: Verify roles can be created, permission-assigned, edited, and deleted.

```
1. Visit GET /admin/roles
   Expect: All seeded roles listed; permission matrix shown grouped by category

2. POST /admin/roles (create)
   Body:   name=Store Manager, permission[]={product index id}, permission[]={product edit id}
   Expect: New role "Store Manager" appears in list with 2 permissions

3. Visit GET /admin/roles/{id}/edit
   Expect: Role edit form loads; current permissions pre-checked

4. PUT /admin/roles/{id} (update permissions)
   Body:   name=Store Manager, permission[]={different permission ids}
   Expect: Redirect to /admin/roles; permissions updated (old ones removed)

5. DELETE /admin/roles/{id}
   Expect: Role deleted; no longer appears in list

6. POST /admin/roles with empty name
   Expect: Validation error — name field required
```

---

## Scenario 4 — Profile Update

**Goal**: Verify authenticated admin can update their own profile.

```
1. Visit GET /admin/edit-profile
   Expect: Profile form loads with authenticated admin data pre-filled

2. POST /admin/update-profile (without new image or password)
   Body:   name=New Name, email=admin@admin.com, phone=0500000001
   Expect: Redirect to /admin/dashboard; name updated

3. POST /admin/update-profile (with new image)
   Body:   name=New Name, image={file upload}
   Expect: Old image deleted from public/uploads/admin/; new image stored at 70% quality

4. POST /admin/update-profile (with new password)
   Body:   password=newpassword, password_confirmation=newpassword
   Expect: Login with newpassword succeeds after update
```

---

## Scenario 5 — Seeded Data Verification

**Goal**: Verify all 46 permissions and Super Admin role are correctly seeded.

```
php artisan tinker --execute '
  echo "Permissions: " . \Spatie\Permission\Models\Permission::where("guard_name","admin")->count() . "\n";
  echo "Roles: " . \Spatie\Permission\Models\Role::where("guard_name","admin")->count() . "\n";
  echo "Admins: " . \Modules\Admin\app\Models\Admin::count() . "\n";
'

Expected output:
  Permissions: 46
  Roles: 1
  Admins: 1
```

---

## Scenario 6 — Bilingual Views

**Goal**: Verify both locales render without error.

```
1. Add ?lang=en to any admin URL — all labels render in English
2. Add ?lang=ar to any admin URL — all labels render in Arabic; RTL CSS applied
   (or toggle via the locale-switcher component if implemented)
```

---

## Key File Locations After Implementation

| Artifact | Path |
|---|---|
| Admin Model | `Modules/Admin/app/Models/Admin.php` |
| AdminDto | `Modules/Admin/app/DTOs/AdminDto.php` |
| AdminService | `Modules/Admin/app/Services/AdminService.php` |
| RoleService | `Modules/Admin/app/Services/RoleService.php` |
| AdminAuthController | `Modules/Admin/app/Http/Controllers/Admin/AdminAuthController.php` |
| AdminController | `Modules/Admin/app/Http/Controllers/Admin/AdminController.php` |
| RoleController | `Modules/Admin/app/Http/Controllers/Admin/RoleController.php` |
| FormRequests | `Modules/Admin/app/Http/Requests/` |
| Admins migration | `Modules/Admin/database/migrations/xxxx_create_admins_table.php` |
| Seeder | `Modules/Admin/database/seeders/AdminDatabaseSeeder.php` |
| Web routes | `Modules/Admin/routes/web.php` |
| Blade views | `Modules/Admin/resources/views/admin/` |
| Lang files | `Modules/Admin/resources/lang/en/admin.php`, `ar/admin.php` |
| Auth config | `config/auth.php` (provider model path updated) |
