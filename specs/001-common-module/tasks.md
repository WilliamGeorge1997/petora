# Tasks: Common Module

**Input**: Design documents from `/specs/001-common-module/`

**Prerequisites**: plan.md, spec.md, research.md, data-model.md

**Tests**: Tests are explicitly NOT required for this project per the Constitution.

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)
- Include exact file paths in descriptions

## Path Conventions

- All paths are relative to repository root (`g:\William\Projects\EnvKit - Projects\petora`)

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Module initialization and foundation structure

- [x] T001 Initialize Common module: `php artisan module:make Common --no-interaction`
- [x] T002 [P] Create `Modules/Common/app/Helpers` directory
- [x] T003 [P] Create `Modules/Common/app/Services` directory

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core infrastructure that MUST be complete before ANY user story can be implemented

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

- [x] T004 Setup Database Seeder structure in `database/seeders/DatabaseSeeder.php` to call `CommonDatabaseSeeder`

**Checkpoint**: Foundation ready - user story implementation can now begin in parallel

---

## Phase 3: User Story 1 - Shared UI Components & Layouts (Priority: P1) 🎯 MVP

**Goal**: As a developer, I want to use reusable Blade layouts, includes, and components so that the Admin Panel UI is consistent and duplication is eliminated.

**Independent Test**: Verify that a blank module can extend the master layout and display the standard sidebar and navbar properly.

### Implementation for User Story 1

- [x] T005 [P] [US1] Create layout view in `Modules/Common/resources/views/layouts/master.blade.php`
- [x] T006 [P] [US1] Create navbar view in `Modules/Common/resources/views/includes/navbar.blade.php`
- [x] T007 [P] [US1] Create sidebar view in `Modules/Common/resources/views/includes/sidebar.blade.php`
- [x] T008 [P] [US1] Create footer view in `Modules/Common/resources/views/includes/footer.blade.php`
- [x] T009 [P] [US1] Create css include view in `Modules/Common/resources/views/includes/css.blade.php`
- [x] T010 [P] [US1] Create js include view in `Modules/Common/resources/views/includes/js.blade.php`
- [x] T011 [P] [US1] Create alert component view in `Modules/Common/resources/views/components/alert.blade.php`
- [x] T012 [P] [US1] Create modal component view in `Modules/Common/resources/views/components/modal.blade.php`

**Checkpoint**: At this point, User Story 1 should be fully functional and testable independently

---

## Phase 4: User Story 2 - Settings Management (Priority: P2)

**Goal**: As a Super Admin, I want to manage general application settings so that I can configure the system globally without code changes.

**Independent Test**: Can be tested by navigating to settings in the admin panel, updating a value, and verifying it is saved and retrievable globally.

### Implementation for User Story 2

- [x] T013 [P] [US2] Create Setting migration: `php artisan module:make-migration create_settings_table Common` (with key, display, value (text), type)
- [x] T014 [US2] Create Setting model in `Modules/Common/app/Models/Setting.php` (with $fillable fields)
- [x] T015 [US2] Populate `Modules/Common/Database/Seeders/CommonDatabaseSeeder.php` with initial Setting rows from data-model
- [x] T016 [P] [US2] Create `CommonService` in `Modules/Common/app/Services/CommonService.php` with `saveSetting`, `logs`, `removeImage`
- [x] T017 [US2] Create Web Controller `Modules/Common/app/Http/Controllers/CommonController.php` with `HasMiddleware` logic
- [x] T018 [US2] Define admin routes in `Modules/Common/routes/web.php` using `Route::resource()->names('admin.common')`

**Checkpoint**: At this point, User Stories 1 AND 2 should both work independently

---

## Phase 5: User Story 3 - Public API Information Endpoints (Priority: P3)

**Goal**: As a Mobile Client/Driver, I want to retrieve static pages (terms, about, privacy) and global variables (tax, social links) so that I can display them in the app.

**Independent Test**: Call the API endpoints and ensure correct localized data is returned.

### Implementation for User Story 3

- [x] T019 [P] [US3] Create API Controller `Modules/Common/app/Http/Controllers/Api/CommonController.php` with `HasMiddleware` logic
- [x] T020 [US3] Implement endpoints for terms, privacy, about, socials, tax in the API Controller using `CommonService` or `Setting` model
- [x] T021 [US3] Define unnamed API routes in `Modules/Common/routes/api.php`

**Checkpoint**: All user stories should now be independently functional

---

## Phase 6: Core Utility Helpers

**Purpose**: Implement the shared utilities translated from the Juicy project for global use.

- [x] T022 [P] Create `Modules/Common/app/Helpers/ResponseHelper.php` with `success()`, `failure()`, and `getCaseCollection()`
- [x] T023 [P] Create `Modules/Common/app/Helpers/UploaderHelper.php` (no webp conversion, 70% quality, original extensions)

---

## Phase 7: Polish & Cross-Cutting Concerns

**Purpose**: Improvements that affect multiple user stories

- [x] T026 Check module configuration and providers (if any adjustments needed)
- [x] T027 Run quickstart.md validation

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies - can start immediately
- **Foundational (Phase 2)**: Depends on Setup completion - BLOCKS all user stories
- **User Stories (Phase 3-5)**: All depend on Foundational phase completion
- **Core Utility Helpers (Phase 6)**: Depends on Setup (Phase 1)
- **Polish (Final Phase)**: Depends on all desired user stories being complete

### User Story Dependencies

- **User Story 1 (P1)**: Independent.
- **User Story 2 (P2)**: Independent.
- **User Story 3 (P3)**: Depends on US2 (Setting model/seeder).

### Implementation Strategy

### MVP First (User Story 1 Only)

1. Complete Phase 1: Setup
2. Complete Phase 2: Foundational 
3. Complete Phase 3: User Story 1
4. **STOP and VALIDATE**: Test User Story 1 independently

### Incremental Delivery

1. Complete Setup + Foundational
2. Add User Story 1 → Test independently
3. Add User Story 2 → Test independently
4. Add User Story 3 → Test independently
5. Add Core Utility Helpers
