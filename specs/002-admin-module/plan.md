# Implementation Plan: Admin Module

**Branch**: `002-admin-module` | **Date**: 2026-08-24 | **Spec**: [spec.md](./spec.md)

**Input**: Feature specification from `specs/002-admin-module/spec.md`

## Summary

Build the Admin Module for the Petora backend dashboard. This module delivers session-based admin authentication (login/logout/profile), admin user CRUD with role assignment, and role & permission management — mirroring the Juicy reference application but ported to nwidart v13 / Laravel 13 / PHP 8.5 / Sanctum / spatie/laravel-permission v8 as dictated by the Petora Constitution.

## Technical Context

**Language/Version**: PHP 8.5

**Primary Dependencies**:
- `laravel/framework` v13.x
- `nwidart/laravel-modules` v13
- `spatie/laravel-permission` v8
- `spatie/laravel-activitylog` v5
- `intervention/image` v4 (built-in wrapper)

**Storage**: MySQL / MariaDB (Admins, Roles, Permissions)

**Testing**: None required (per Constitution)

**Target Platform**: Web server (Blade dashboard)

**Project Type**: Laravel Modular Application (nwidart)

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| Principle | Status | Notes |
|---|---|---|
| I. Modular-First | ✅ PASS | All code inside `Modules/Admin/` |
| II. Services–DTOs–Controllers | ✅ PASS | AdminService, RoleService, AdminDto |
| III. Common Module | ✅ PASS | Relies on `common::layouts.master` |
| IVb. Validation in FormRequests | ✅ PASS | Strict `FormRequest` validation for all input |
| V. No Tests | ✅ PASS | No test generation |
| VII. Permissions | ✅ PASS | Uses Spatie with Petora `category` and `display` columns |
| VIII. Auth | ✅ PASS | Sanctum interface on model, Web session for login |
| IX. Engineering Standards | ✅ PASS | `HasMiddleware` used for controllers (Laravel 13) |

## Project Structure

### Documentation (this feature)

```text
specs/002-admin-module/
├── plan.md              # This file
├── research.md          # Phase 0 output
├── data-model.md        # Phase 1 output
├── quickstart.md        # Phase 1 output
└── tasks.md             # Phase 2 output (via /speckit-tasks)
```

### Source Code

```text
Modules/Admin/
├── app/
│   ├── DTOs/AdminDto.php
│   ├── Http/
│   │   ├── Controllers/Admin/
│   │   │   ├── AdminAuthController.php
│   │   │   ├── AdminController.php
│   │   │   └── RoleController.php
│   │   └── Requests/ (LoginRequest, UpdateProfileRequest, StoreAdminRequest, UpdateAdminRequest, StoreRoleRequest, UpdateRoleRequest)
│   ├── Models/Admin.php
│   ├── Policies/AdminPolicy.php
│   └── Services/
│       ├── AdminService.php
│       └── RoleService.php
├── database/
│   ├── migrations/xxxx_create_admins_table.php
│   └── seeders/AdminDatabaseSeeder.php
├── resources/
│   ├── lang/{en,ar}/admin.php
│   └── views/admin/
│       ├── login.blade.php
│       ├── dashboard.blade.php
│       ├── edit-profile.blade.php
│       ├── admins/ (index, create, edit)
│       └── roles/ (index, edit)
└── routes/web.php
```

**Structure Decision**: A standard nwidart module matching the Juicy backend pattern but refactored to Laravel 13 strict boundaries. Controllers utilize `HasMiddleware`, services handle business logic, and Blade templates handle UI rendering grouped into `admin/admins` and `admin/roles`.
