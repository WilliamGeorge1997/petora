# Tasks: Store Module

**Feature**: Store Module
**Branch**: `004-store-module`

## Phase 1: Setup (Module Initialization)

**Purpose**: Scaffold the base module using Nwidart commands to match the Company module structure.

- [ ] T001 Generate base module: `php artisan module:make Store --no-interaction`

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core infrastructure that MUST be complete before the user story implementation.

- [x] T002 [P] Create Model: `php artisan module:make-model Store Store --no-interaction`
- [x] T003 [P] Create Migration: `php artisan module:make-migration create_stores_table Store --no-interaction`
- [x] T004 Define Migration: Edit `Modules/Store/database/migrations/*_create_stores_table.php` to add `title` (json), `address` (json), `phone`, `is_active`, `lat`, `long`
- [x] T005 Define Model: Edit `Modules/Store/app/Models/Store.php` to include `HasTranslations`, `LogsActivity`, fillables, and `serializeDate()`

**Checkpoint**: Foundation ready - Database schema and Eloquent model are configured.

---

## Phase 3: User Story 1 - Manage Stores (Priority: P1) → MVP

**Goal**: As an admin, I want to be able to create, read, update, and delete (CRUD) stores using the exact same architectural pattern as the Company module.

**Independent Test**: Can be fully tested by creating a new store through the API or Admin interface and verifying all attributes are saved correctly, especially the JSON translatable fields.

### Implementation for User Story 1

- [x] T006 [P] [US1] Create Controller: `php artisan module:make-controller StoreController Store --no-interaction`
- [x] T007 [P] [US1] Create API Controller: Skipped, Company module doesn't use Api directory for standard resources.
- [x] T008 [P] [US1] Create Request: `php artisan module:make-request StoreRequest Store --no-interaction`
- [x] T009 [P] [US1] Create Policy: `php artisan module:make-policy StorePolicy Store --no-interaction`
- [x] T010 [P] [US1] Create Seeder: `php artisan module:make-seed StoreDatabaseSeeder Store --no-interaction`
- [x] T011 [US1] Create Service: Create `Modules/Store/app/Services/StoreService.php` with the standard methods.
- [x] T012 [US1] Create DTO: Create `Modules/Store/app/DTOs/StoreDto.php` using constructor property promotion.
- [x] T013 [US1] Implement Controller Logic: Wire `StoreController` to use `StoreService` and `StoreDto`.
- [x] T014 [US1] Configure Routes: Define resourceful routes in `Modules/Store/routes/web.php` and `api.php`.
- [x] T015 [US1] Setup Blade Views: Create `index.blade.php`, `create.blade.php`, and `edit.blade.php` in `Modules/Store/resources/views/stores/`.
- [x] T016 [US1] Setup Translations: Create `general.php` in `Modules/Store/lang/en/` and `Modules/Store/lang/ar/`.
- [x] T017 [US1] Load Translations: Update `Modules/Store/app/Providers/StoreServiceProvider.php` to manually load language files per Nwidart docs.
- [x] T018 [US1] Register Seeder: Call `StoreDatabaseSeeder` in `database/seeders/DatabaseSeeder.php`.

**Checkpoint**: At this point, User Story 1 should be fully functional and testable independently.

---

## Phase N: Polish & Cross-Cutting Concerns

**Purpose**: Improvements and final validation

- [ ] T019 Run quickstart.md validation locally to verify CRUD operations and translations

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies - can start immediately
- **Foundational (Phase 2)**: Depends on Setup completion - BLOCKS all user stories
- **User Stories (Phase 3+)**: Depend on Foundational phase completion
- **Polish (Final Phase)**: Depends on User Story 1 completion

### User Story Dependencies

- **User Story 1 (P1)**: Can start after Foundational (Phase 2) - No dependencies on other stories

### Parallel Opportunities

- The `php artisan module:make-*` commands in Phase 2 and 3 can be run concurrently or back-to-back.
- The Blade templates, DTO, and translation files can be stubbed out simultaneously.
