# Tasks: Clinic Module

## Phase 1: Setup

**Purpose**: Project initialization and basic structure

- [ ] T001 Create the Clinic module using `php artisan module:make Clinic --no-interaction`

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core infrastructure that MUST be complete before ANY user story can be implemented

**CRITICAL**: No user story work can begin until this phase is complete

- [ ] T002 Generate migration `php artisan module:make-migration create_clinics_table Clinic --no-interaction`
- [ ] T003 Generate model `php artisan module:make-model Clinic Clinic --no-interaction`
- [ ] T004 Generate seeder `php artisan module:make-seeder ClinicDatabaseSeeder Clinic --no-interaction`
- [ ] T005 Update migration in `Modules/Clinic/database/migrations/*_create_clinics_table.php` with columns
- [ ] T006 Update model in `Modules/Clinic/app/Models/Clinic.php` with fillable, translations, and scopes
- [ ] T007 Register seeder in `database/seeders/DatabaseSeeder.php` and run migration

**Checkpoint**: Foundation ready - user story implementation can now begin in parallel

---

## Phase 3: User Story 1 - Create and Manage Clinics (Priority: P1)

**Goal**: Super Admins and Managers need to be able to create, view, edit, and delete clinics within the admin dashboard.

**Independent Test**: Can be fully tested by creating a new Clinic via the Admin Panel and verifying its presence in the database and API.

### Implementation for User Story 1

- [x] T008 [P] [US1] Generate Admin Controller using `php artisan module:make-controller ClinicController Clinic --no-interaction`
- [x] T009 [P] [US1] Generate Form Request using `php artisan module:make-request ClinicRequest Clinic --no-interaction`
- [x] T010 [P] [US1] Generate Policy using `php artisan module:make-policy ClinicPolicy Clinic --no-interaction`
- [x] T011 [P] [US1] Create DTO in `Modules/Clinic/app/DTOs/ClinicDto.php`
- [x] T012 [P] [US1] Create Service in `Modules/Clinic/app/Services/ClinicService.php`
- [x] T013 [P] [US1] Implement validation rules in `Modules/Clinic/app/Http/Requests/ClinicRequest.php`
- [x] T014 [US1] Implement DTO mapping logic in `Modules/Clinic/app/DTOs/ClinicDto.php`
- [x] T015 [US1] Implement CRUD methods in `Modules/Clinic/app/Services/ClinicService.php`
- [x] T016 [US1] Implement authorization logic in `Modules/Clinic/app/Policies/ClinicPolicy.php` and register in `ClinicServiceProvider.php`
- [x] T017 [P] [US1] Create translation files for en/ar in `Modules/Clinic/lang/` and register in `ClinicServiceProvider.php`
- [x] T018 [US1] Implement Controller methods in `Modules/Clinic/app/Http/Controllers/ClinicController.php`
- [x] T019 [US1] Define Admin web routes in `Modules/Clinic/routes/web.php`
- [x] T020 [US1] Create Blade views (`index`, `create`, `edit`) in `Modules/Clinic/resources/views/clinics/`

**Checkpoint**: At this point, User Story 1 should be fully functional and testable independently

---

## Phase 4: User Story 2 - API Access for Clients (Priority: P2)

**Goal**: Clients using the Flutter app need to be able to fetch a list of active clinics

**Independent Test**: Can be tested by hitting the GET `/api/clinic` endpoint and verifying the JSON response.

### Implementation for User Story 2

- [x] T021 [P] [US2] Generate API Controller using `php artisan module:make-controller Api/ClinicController Clinic --no-interaction`
- [x] T022 [US2] Implement API listing method in `Modules/Clinic/app/Http/Controllers/Api/ClinicController.php`
- [x] T023 [US2] Define API routes in `Modules/Clinic/routes/api.php`

**Checkpoint**: At this point, User Stories 1 AND 2 should both work independently

---

## Phase 5: Polish & Cross-Cutting Concerns

**Purpose**: Improvements that affect multiple user stories

- [x] T024 Run quickstart.md validation to ensure end-to-end functionality

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies - can start immediately
- **Foundational (Phase 2)**: Depends on Setup completion - BLOCKS all user stories
- **User Stories (Phase 3+)**: All depend on Foundational phase completion
  - User Story 1 (P1) is the MVP.
  - User Story 2 (P2) can be implemented sequentially after US1.

### Parallel Opportunities

- Artisan generator commands (T008, T009, T010, T021) can run in parallel.
- Setting up translations (T017) and DTOs (T011) can run in parallel.

## Implementation Strategy

### MVP First (User Story 1 Only)

1. Complete Phase 1: Setup
2. Complete Phase 2: Foundational (CRITICAL - blocks all stories)
3. Complete Phase 3: User Story 1
4. **STOP and VALIDATE**: Test User Story 1 independently in Admin Panel

### Incremental Delivery

1. Complete MVP (Admin UI).
2. Complete Phase 4: API Access for Clients.
3. Validate overall feature completion.
