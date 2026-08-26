# Implementation Plan: Clinic Module

**Branch**: `005-clinic-module` | **Date**: 2026-08-26 | **Spec**: [spec.md](file:///g:/William/Projects/EnvKit%20-%20Projects/petora/specs/005-clinic-module/spec.md)

**Input**: Feature specification from `/specs/005-clinic-module/spec.md`

## Summary

Implement a new `Clinic` module using `nwidart/laravel-modules` for veterinary clinic management. It will be a standalone entity that strictly mirrors the `Store` module's canonical structure.

## Technical Context

**Language/Version**: PHP 8.5

**Primary Dependencies**: Laravel 13.17, nwidart/laravel-modules 13.0, spatie/laravel-permission 8.3, spatie/laravel-translatable 6.14, spatie/laravel-activitylog 5.1

**Storage**: MySQL / MariaDB

**Testing**: Tests explicitly skipped per Constitution (Principle V)

**Target Platform**: Linux server, API consumed by Flutter app

**Project Type**: Laravel backend module

**Performance Goals**: Standard CRUD response time (< 200ms)

**Constraints**: Must strictly follow the `Store` module blueprint (Principle XI). Standalone entity without a `company_id`.

**Scale/Scope**: Single CRUD module + API endpoint.

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

- [x] Must be inside a dedicated module (Principle I)
- [x] Must follow Services–DTOs–Controller Layering (Principle II)
- [x] Must use API Response Contract helpers (Principle IV)
- [x] Validation in FormRequests only (Principle IVb)
- [x] No tests required (Principle V)
- [x] Must be fully bilingual in Arabic/English (Principle VI)
- [x] Permissions & Activity Logging implemented (Principle VII)
- [x] Sanctum authentication integration (Principle VIII)
- [x] Admin Panel authorization via Policies & Gates (Principle X)
- [x] MUST strictly mirror the **Store** module's structure (Principle XI)

## Project Structure

### Documentation (this feature)

```text
specs/005-clinic-module/
├── plan.md              # This file
├── research.md          # Phase 0 output
├── data-model.md        # Phase 1 output
├── quickstart.md        # Phase 1 output
└── contracts/           # Phase 1 output
```

### Source Code (repository root)

```text
Modules/Clinic/
├── app/
│   ├── DTOs/
│   │   └── ClinicDto.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── ClinicController.php
│   │   │   └── ClinicController.php
│   │   └── Requests/
│   │       └── ClinicRequest.php
│   ├── Models/
│   │   └── Clinic.php
│   ├── Policies/
│   │   └── ClinicPolicy.php
│   ├── Providers/
│   │   └── ClinicServiceProvider.php
│   └── Services/
│       └── ClinicService.php
├── database/
│   ├── migrations/
│   │   └── ..._create_clinics_table.php
│   └── seeders/
│       └── ClinicDatabaseSeeder.php
├── resources/
│   ├── lang/
│   │   ├── ar/
│   │   │   ├── attribute.php
│   │   │   ├── general.php
│   │   │   └── message.php
│   │   └── en/
│   │       ├── attribute.php
│   │       ├── general.php
│   │       └── message.php
│   └── views/
│       └── clinics/
│           ├── create.blade.php
│           ├── edit.blade.php
│           └── index.blade.php
└── routes/
    ├── api.php
    └── web.php
```

**Structure Decision**: The above layout replicates the `Store` module exactly as prescribed by Principle XI of the constitution.
