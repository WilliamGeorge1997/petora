# Implementation Plan: Company Module

**Branch**: `[003-company-module]` | **Date**: 2026-08-25 | **Spec**: [spec.md](spec.md)

**Input**: Feature specification from `/specs/003-company-module/spec.md`

## Summary

Implement the Company Module to allow administrators to manage business entities on the platform, following the Petora Backend Constitution's Modular-First Architecture (nwidart/laravel-modules), Services-DTO-Controller layering, and built-in helper patterns.

## Technical Context

**Language/Version**: PHP 8.5

**Primary Dependencies**: Laravel 13.17, nwidart/laravel-modules v13, spatie/laravel-translatable, spatie/laravel-activitylog, spatie/laravel-permission

**Storage**: MySQL / MariaDB (JSON columns for translations)

**Testing**: No testing required (Constitution Principle V).

**Target Platform**: Linux server, API consumed by Flutter app.

**Project Type**: Laravel Module (Backend API & Admin Dashboard)

**Performance Goals**: Dashboard pagination 50 per page (Admin), 20 per page (API).

**Constraints**: Must strictly follow nwidart v13 architecture, no tests, no inline comments, no npm/vite.

**Scale/Scope**: Top-level entity.

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

- [x] Principle I (Modular-First Architecture): Handled by using `php artisan module:make Company`.
- [x] Principle II (Services–DTOs–Controller Layering): Handled by generating `CompanyService`, `CompanyDto`, and thin `CompanyController`.
- [x] Principle IV & IVb (Response Contract & FormRequest): Using `success()`/`failure()` helpers and `CompanyRequest`.
- [x] Principle V (No Tests Required): Ensured by omitting test generation.
- [x] Principle VI (Bilingual by Design): Handled by `HasTranslations` on `title` and `address` (JSON columns).
- [x] Principle VII (Permissions & Activity Logging): Implementing `spatie/laravel-permission` and `LogsActivity`.
- [x] Principle IX (Engineering Standards): Code structure relies on Dependency Injection and early returns (Fail Fast).
- [x] Principle X (Policies & Gates): Generating `CompanyPolicy` for Admin Dashboard access control.

## Project Structure

### Documentation (this feature)

```text
specs/003-company-module/
├── plan.md              # This file (/speckit-plan command output)
├── research.md          # Phase 0 output (/speckit-plan command)
├── data-model.md        # Phase 1 output (/speckit-plan command)
├── quickstart.md        # Phase 1 output (/speckit-plan command)
├── contracts/           # Phase 1 output (/speckit-plan command)
└── tasks.md             # Phase 2 output (/speckit-tasks command - NOT created by /speckit-plan)
```

### Source Code (repository root)

```text
Modules/Company/
├── app/
│   ├── DTOs/
│   │   └── CompanyDto.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── CompanyController.php
│   │   │   └── Admin/
│   │   │       └── CompanyController.php
│   │   └── Requests/
│   │       └── CompanyRequest.php
│   ├── Models/
│   │   └── Company.php
│   ├── Policies/
│   │   └── CompanyPolicy.php
│   ├── Providers/
│   │   ├── CompanyServiceProvider.php
│   │   └── RouteServiceProvider.php
│   └── Services/
│       └── CompanyService.php
├── database/
│   ├── migrations/
│   │   └── [timestamp]_create_companies_table.php
│   └── seeders/
│       └── CompanyDatabaseSeeder.php
├── resources/
│   ├── lang/
│   │   ├── en/
│   │   └── ar/
│   └── views/
│       └── company/
│           ├── index.blade.php
│           ├── create.blade.php
│           └── edit.blade.php
└── routes/
    ├── api.php
    └── web.php
```

**Structure Decision**: Selected standard nwidart/laravel-modules architecture (Option 1 equivalent for Petora) keeping with the Petora Backend Constitution constraints.
