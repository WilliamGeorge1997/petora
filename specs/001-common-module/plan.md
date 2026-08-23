# Implementation Plan: Common Module

**Branch**: `[001-common-module]` | **Date**: 2026-08-23 | **Spec**: [spec.md](spec.md)

**Input**: Feature specification from `/specs/001-common-module/spec.md`

## Summary

Build the foundational `Common` module containing global application settings, public static API endpoints, shared Blade layouts, and utility helpers for SMS, FCM, Responses, and Uploads.

## Technical Context

**Language/Version**: PHP 8.5, Laravel 13.17
**Primary Dependencies**: `nwidart/laravel-modules` v13, `spatie/laravel-permission`, Sanctum
**Storage**: MySQL/MariaDB
**Testing**: None (No tests required currently)
**Target Platform**: Linux Server, Flutter Mobile Clients
**Project Type**: Laravel Modular Monolith (Web Admin + REST API)
**Performance Goals**: N/A
**Constraints**: Follow Constitution non-negotiables exactly
**Scale/Scope**: 1 module (Common) out of 12

## Constitution Check

*GATE: Passed. The design aligns with all non-negotiables in `constitution.md`.*
- nwidart v13 architecture respected.
- Services injected via DI.
- API endpoints unnamed, Web endpoints use `Route::resource()`.
- Controllers are thin, logic in `CommonService`.
- Helpers conform to Juicy patterns.

## Project Structure

### Documentation (this feature)

```text
specs/001-common-module/
├── plan.md              
├── research.md          
├── data-model.md        
├── quickstart.md        
└── contracts/           
```

### Source Code (repository root)

```text
Modules/Common/
├── app/
│   ├── Http/Controllers/
│   │   ├── CommonController.php (Web Admin)
│   │   └── Api/CommonController.php (API)
│   ├── Models/
│   │   └── Setting.php
│   ├── Services/
│   │   └── CommonService.php
│   └── Helpers/
│       ├── FCMService.php
│       ├── SmsService.php
│       ├── ResponseHelper.php
│       └── UploaderHelper.php
├── Database/
│   ├── Migrations/
│   └── Seeders/
│       └── CommonDatabaseSeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── master.blade.php
│       ├── includes/
│       │   ├── navbar.blade.php
│       │   ├── sidebar.blade.php
│       │   ├── footer.blade.php
│       │   ├── css.blade.php
│       │   └── js.blade.php
│       └── components/
│           ├── alert.blade.php
│           └── modal.blade.php
└── routes/
    ├── web.php
    └── api.php
```

**Structure Decision**: Standard nwidart `laravel-modules` structure aligned with Constitution Rule II.
