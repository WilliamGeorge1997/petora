# Implementation Tasks: Company Module

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Project initialization and basic structure

- [x] T001 Create Company module structure using `php artisan module:make Company`

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core infrastructure that MUST be complete before ANY user story can be implemented

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

- [x] T002 Create database migration for `companies` table using `php artisan module:make-migration create_companies_table Company`
- [x] T003 Create `Company` model using `php artisan module:make-model Company Company`
- [x] T004 Create `CompanyDatabaseSeeder` for permissions using `php artisan module:make-seeder CompanyDatabaseSeeder Company`
- [x] T005 [P] Create `CompanyDto` in `Modules/Company/app/DTOs/CompanyDto.php`

**Checkpoint**: Foundation ready - user story implementation can now begin

---

## Phase 3: User Story 1 - Admin Manages Company Catalog (Priority: P1) 🚀 MVP

**Goal**: Administrators can create, view, update, and delete company records.

**Independent Test**: Can be fully tested by navigating to the Companies section in the admin dashboard and successfully creating, viewing, and modifying a company record.

### Implementation for User Story 1

- [x] T006 [P] [US1] Create `CompanyRequest` for validation using `php artisan module:make-request CompanyRequest Company`
- [x] T007 [P] [US1] Create `CompanyPolicy` for authorization using `php artisan module:make-policy CompanyPolicy Company`
- [x] T008 [US1] Implement `CompanyService` CRUD logic in `Modules/Company/app/Services/CompanyService.php` (findAll, findById, findBy, save, update, delete)
- [x] T009 [US1] Create Admin `CompanyController` using `php artisan module:make-controller Admin/CompanyController Company`
- [x] T010 [US1] Implement Admin routing (web.php) for Company CRUD in `Modules/Company/routes/web.php`
- [x] T011 [P] [US1] Implement `index.blade.php` view in `Modules/Company/resources/views/company/index.blade.php`
- [x] T012 [P] [US1] Implement `create.blade.php` view in `Modules/Company/resources/views/company/create.blade.php`
- [x] T013 [P] [US1] Implement `edit.blade.php` view in `Modules/Company/resources/views/company/edit.blade.php`
- [x] T014 [US1] Add English and Arabic translations for the UI in `Modules/Company/resources/lang/en/` and `ar/`

**Checkpoint**: At this point, User Story 1 should be fully functional and testable independently in the Admin Dashboard. (API tasks skipped per user request).

---

## Phase 4: User Story 2 - Toggle Company Active Status (Priority: P2)

**Goal**: Administrators can toggle the active status of a company without deleting it.

**Independent Test**: Can be fully tested by clicking a toggle/button on the company list to change its status, then verifying the company is updated in the database.

### Implementation for User Story 2

- [x] T015 [P] [US2] Add `activate` method to `CompanyService` in `Modules/Company/app/Services/CompanyService.php`
- [x] T016 [US2] Add `activate` method to Admin `CompanyController` in `Modules/Company/app/Http/Controllers/Admin/CompanyController.php`
- [x] T017 [US2] Add toggle route in `Modules/Company/routes/web.php`
- [x] T018 [US2] Add status toggle button to the datatable in `Modules/Company/resources/views/company/index.blade.php`

**Checkpoint**: At this point, User Stories 1 AND 2 should both work independently

---

## Phase N: Polish & Cross-Cutting Concerns

**Purpose**: Improvements that affect multiple user stories

- [x] T019 Run quickstart.md validation scenarios manually (Dashboard creation and toggle)

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies - can start immediately
- **Foundational (Phase 2)**: Depends on Setup completion - BLOCKS all user stories
- **User Stories (Phase 3+)**: All depend on Foundational phase completion
- **Polish (Final Phase)**: Depends on all desired user stories being complete

### User Story Dependencies

- **User Story 1 (P1)**: Can start after Foundational (Phase 2)
- **User Story 2 (P2)**: Integrates with US1 (modifies the US1 controller and views), should start after US1 is mostly complete.

### Parallel Opportunities

- All views (`index`, `create`, `edit`) can be developed in parallel once the controller/routes are defined.
- Form Requests and Policies can be generated simultaneously.
