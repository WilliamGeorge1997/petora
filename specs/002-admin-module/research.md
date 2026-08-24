# Research: Admin Module

**Phase**: 0 — Outline & Research
**Date**: 2026-08-24
**Feature**: 002-admin-module

---

## Finding 1 — Admin Guard Configuration

**Decision**: The `admin` Sanctum guard is already registered in `config/auth.php` using the **`session`** driver (not Sanctum token driver). This is correct for a web dashboard — session-based login with `Auth::guard("admin")->attempt()` is the right approach. No config changes needed for the guard itself.

**Rationale**: Dashboard authentication must use sessions because Blade views rely on server-side state. Sanctum token issuance is for Flutter API consumers only.

**Critical issue found**: `config/auth.php` provider for `admins` currently points to `Modules\Admin\Entities\Admin` (the old Laravel 8 / Juicy namespace). This MUST be updated to `Modules\Admin\app\Models\Admin` (the nwidart v13 path) when the model is created, otherwise login will fail with a model-not-found error.

**Alternatives considered**: Sanctum token guard for the dashboard — rejected because it requires stateless clients and cannot work with standard Blade form submissions.

---

## Finding 2 — Permissions Table Custom Columns

**Decision**: The `permissions` table already has `category` (string) and `display` (string) columns. **No additional migration is required.** The `create_permission_tables` migration (batch 1, already run) includes these Petora custom columns.

**Rationale**: Confirmed via `php artisan tinker` introspecting `Schema::getColumnListing("permissions")` — output was `[id, name, guard_name, category, display, created_at, updated_at]`.

**Alternatives considered**: Adding a separate migration to add the columns — rejected as unnecessary (columns already exist).

---

## Finding 3 — Admins Table

**Decision**: The `admins` table does **not** exist yet — no migration for it has been run. A new migration must be created as part of this module. Confirmed by `php artisan migrate:status` — no `create_admins_table` entry present.

**Rationale**: The module scaffold created the `database/migrations/` directory but contains no files yet.

**Alternatives considered**: None — the table must be created from scratch.

---

## Finding 4 — Sanctum / HasApiTokens

**Decision**: `laravel/sanctum` is bundled inside `laravel/framework` v13.26.1 — it is NOT a separately-installed Composer package. The `HasApiTokens` trait is available at `Laravel\Sanctum\HasApiTokens`. The `personal_access_tokens` table migration from the framework stub is included in `0001_01_01_000000_create_users_table.php` (already run). No extra installation step is needed.

**Rationale**: Laravel 13 ships with Sanctum pre-installed. The Admin model can use `HasApiTokens` without any additional composer require.

**Alternatives considered**: JWT (Tymon) — rejected per constitution (Sanctum replaces JWT from Juicy).

---

## Finding 5 — Spatie Package Versions

**Decision**: Use the API of the installed versions exactly:
- `spatie/laravel-permission` → **8.3.0** — `HasRoles` trait, `Role::create()`, `Permission::all()->groupBy()`, `syncPermissions()`, `assignRole()`, `guard_name` on Role/Permission constructor.
- `spatie/laravel-activitylog` → **5.1.0** — `LogsActivity` trait, `$logName`, `$logAttributes`, `$logOnlyDirty`, `$submitEmptyLogs` static properties.

**Rationale**: Constitution rule: "Before using any package API, run `composer show` to confirm the installed version. Do NOT assume API signatures."

**Alternatives considered**: N/A — these are already installed and pinned.

---

## Finding 6 — Existing Admin Module State

**Decision**: The Admin module scaffold already exists at `Modules/Admin/`. The following files exist and must be **replaced/filled** (not created from scratch via artisan):
- `Modules/Admin/app/Http/Controllers/AdminController.php` — skeleton stub, will be replaced
- `Modules/Admin/routes/web.php` — exists but mostly empty, will be filled
- `Modules/Admin/routes/api.php` — exists, stays empty (no API for Admin)
- `Modules/Admin/app/Providers/AdminServiceProvider.php` — exists, will need lang registration added
- `Modules/Admin/resources/views/` — exists but empty

All **new** PHP class files (Model, DTOs, Services, FormRequests, Policy) MUST still be created via `php artisan module:make-*` commands per the constitution, as the `app/Models/`, `app/DTOs/`, `app/Services/`, `app/Http/Requests/`, `app/Policies/` subdirectories do not exist yet.

---

## Finding 7 — Auth Config Model Path Update

**Decision**: `config/auth.php` provider `admins.model` must be changed from `Modules\Admin\Entities\Admin` to `Modules\Admin\app\Models\Admin`. This is a **config file edit**, not a migration — performed directly in `config/auth.php` as part of this module's implementation.

**Rationale**: nwidart v13 places all PHP classes under `Modules/{Name}/app/` not `Modules/{Name}/`. Without this fix, `Auth::guard("admin")->attempt()` cannot resolve the Admin model and login will always fail.

---

## Finding 8 — No `lang/` Directory Exists Yet

**Decision**: The module has no `resources/lang/` directory. It must be created with `en/` and `ar/` subdirectory and a `admin.php` translation file in each. The lang loading must be registered in `AdminServiceProvider::boot()` via `$this->loadTranslationsFrom(module_path("Admin", "resources/lang"), "admin")` following nwidart v13 documentation.

---

## Summary of Resolved Unknowns

| Unknown | Resolution |
|---|---|
| Admin guard registered? | ✅ Yes — session driver, already in config/auth.php |
| Permissions `category`/`display` columns exist? | ✅ Yes — confirmed via schema introspection |
| Admins table exists? | ❌ No — migration must be created |
| Sanctum available? | ✅ Yes — bundled in Laravel 13 framework |
| Spatie permission API (v8.3.0) | ✅ Confirmed — use `HasRoles`, `syncPermissions`, `assignRole` |
| Spatie activitylog API (v5.1.0) | ✅ Confirmed — static log properties on model |
| Auth config model path correct? | ❌ Stale — points to Entities\Admin, must update to app\Models\Admin |
| Lang files exist? | ❌ No — must create en/ and ar/ lang directories |
