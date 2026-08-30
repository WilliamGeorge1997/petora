# Implementation Plan: Country Module

**Branch**: `006-country-module` | **Date**: 2026-08-30 | **Spec**: [spec.md](./spec.md)

**Input**: Feature specification from `specs/006-country-module/spec.md`

## Summary

The Country module will implement CRUD operations for Countries, Cities, and Zones. It will strictly follow the Store module's Service-DTO-Controller architecture, utilizing Spatie's translatable for JSON titles, and exposing both an Admin Blade interface and a Sanctum-protected JSON API.

## Technical Context

**Language/Version**: PHP 8.5

**Primary Dependencies**: Laravel 13.17, nwidart/laravel-modules 13.0, spatie/laravel-translatable 6.14, spatie/laravel-permission 8.3

**Storage**: MySQL / MariaDB (JSON columns for translations)

**Testing**: No Tests Required (as per Constitution)

**Target Platform**: Linux server / Laravel Application

**Project Type**: Laravel Module

**Constraints**: Must strictly mirror the `Store` module structure. DTOs, Services, and Controllers layering is non-negotiable. Admin UI must be server-rendered Blade, bilingual by design.

**Scale/Scope**: 3 Tables (countries, cities, zones). 3 sets of CRUD (Admin Controllers + API Controllers + Services + DTOs).

## Constitution Check

*GATE: Passed*

- **Modular-First Architecture (Principle I)**: Valid, creating the `Country` module.
- **Services–DTOs–Controller Layering (Principle II)**: Valid, will implement DTOs, Services, and separate API/Admin Controllers.
- **Bilingual by Design (Principle VI)**: Valid, using `spatie/laravel-translatable` for `title` and `lang/` files for Admin UI.
- **The Store Module as the Canonical Structure (Principle XI)**: Valid, this plan explicitly replicates the Store module structure.
- **API Response Contract (Principle IV)**: Valid, will use `success()` and `failure()` helpers from Common module.

## Project Structure

### Documentation (this feature)

```text
specs/006-country-module/
├── plan.md              # This file
├── research.md          # Phase 0 output
├── data-model.md        # Phase 1 output
├── quickstart.md        # Phase 1 output
├── contracts/           # API payloads defined here
└── tasks.md             # To be created by /speckit-tasks
```

### Source Code (repository root)

```text
Modules/Country/
├── app/
│   ├── DTOs/
│   │   ├── CountryDto.php
│   │   ├── CityDto.php
│   │   └── ZoneDto.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── CountryController.php
│   │   │   │   ├── CityController.php
│   │   │   │   └── ZoneController.php
│   │   │   └── Admin/
│   │   │       ├── CountryController.php
│   │   │       ├── CityController.php
│   │   │       └── ZoneController.php
│   │   └── Requests/
│   │       ├── CountryRequest.php
│   │       ├── CityRequest.php
│   │       └── ZoneRequest.php
│   ├── Models/
│   │   ├── Country.php
│   │   ├── City.php
│   │   └── Zone.php
│   ├── Policies/
│   │   ├── CountryPolicy.php
│   │   ├── CityPolicy.php
│   │   └── ZonePolicy.php
│   ├── Providers/
│   │   ├── CountryServiceProvider.php
│   │   └── RouteServiceProvider.php
│   └── Services/
│       ├── CountryService.php
│       ├── CityService.php
│       └── ZoneService.php
├── database/
│   ├── migrations/
│   │   ├── ..._create_countries_table.php
│   │   ├── ..._create_cities_table.php
│   │   └── ..._create_zones_table.php
│   └── seeders/
│       └── CountryDatabaseSeeder.php
├── resources/
│   ├── lang/
│   │   ├── en/general.php
│   │   └── ar/general.php
│   └── views/
│       ├── country/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       ├── city/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       └── zone/
│           ├── index.blade.php
│           ├── create.blade.php
│           └── edit.blade.php
└── routes/
    ├── api.php
    └── web.php
```

**Structure Decision**: Module structure exactly matching nwidart/laravel-modules v13 and Petora's Store module blueprint.

## Complexity Tracking

No violations. Complexity aligns exactly with project constraints.
