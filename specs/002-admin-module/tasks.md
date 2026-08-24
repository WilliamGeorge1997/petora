# Implementation Tasks: Admin Module

**Feature**: 002-admin-module

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Project initialization and basic structure

- [ ] T001 Create project structure per implementation plan (verify/create `Modules/Admin` using `php artisan module:make Admin`)

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core infrastructure that MUST be complete before ANY user story can be implemented

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

- [ ] T002 Create migration for `admins` table with `name`, `email`, `password`, `phone`, `is_active`, `image`.
- [ ] T003 [P] Create `Admin` model in `Modules/Admin/app/Models/Admin.php` implementing `HasRoles`, `HasApiTokens`, `LogsActivity` with proper accessors (`getImageAttribute`).
- [ ] T004 [P] Create `AdminDatabaseSeeder` in `Modules/Admin/database/seeders/AdminDatabaseSeeder.php` to seed the `Super Admin` role, create a default admin, and seed the 46 Petora permissions with `category` and `display` columns.
- [ ] T005 Register `AdminDatabaseSeeder` in main `DatabaseSeeder.php` and run `php artisan migrate:fresh --seed` (or appropriate migration run).
- [ ] T006 Configure `admin` guard in `config/auth.php` (set driver to session, provider to admins).
- [ ] T007 Setup base `routes/web.php` inside `Modules/Admin` with `admin` prefix and name.

**Checkpoint**: Foundation ready - Admin model and permissions exist, guard is configured.

---

## Phase 3: User Story 1 - Admin Authentication (Priority: P1) 🎯 MVP

**Goal**: Session-based login, logout, and profile management for admin users.

**Independent Test**: Navigate to `/admin/login`, log in, edit profile, and log out.

### Implementation for User Story 1

- [ ] T008 [P] [US1] Create `LoginRequest` and `UpdateProfileRequest` in `Modules/Admin/app/Http/Requests/`.
- [ ] T009 [US1] Create `AdminAuthController` in `Modules/Admin/app/Http/Controllers/Admin/AdminAuthController.php` extending `HasMiddleware` for login, logout, and edit profile. MUST use `UploaderHelper` for profile image upload.
- [ ] T010 [P] [US1] Create `login.blade.php` in `Modules/Admin/resources/views/admin/`.
- [ ] T011 [P] [US1] Create `dashboard.blade.php` and `edit-profile.blade.php` in `Modules/Admin/resources/views/admin/` extending `common::layouts.master`.
- [ ] T012 [US1] Wire up login, logout, dashboard, and profile routes in `Modules/Admin/routes/web.php`.

**Checkpoint**: At this point, User Story 1 should be fully functional and testable independently.

---

## Phase 4: User Story 2 - Admin User Management (Priority: P2)

**Goal**: Create, edit, activate/deactivate, and delete other admin accounts.

**Independent Test**: Log in, navigate to admins list, create admin, toggle active, edit, and delete.

### Implementation for User Story 2

- [ ] T013 [P] [US2] Create `AdminDto` using PHP 8.5 constructor property promotion in `Modules/Admin/app/DTOs/AdminDto.php`.
- [ ] T014 [P] [US2] Create `StoreAdminRequest` and `UpdateAdminRequest` in `Modules/Admin/app/Http/Requests/`.
- [ ] T015 [US2] Create `AdminService` in `Modules/Admin/app/Services/AdminService.php` to handle CRUD logic, role assignment, and image uploading (using `UploaderHelper`).
- [ ] T016 [US2] Create `AdminController` in `Modules/Admin/app/Http/Controllers/Admin/AdminController.php` extending `HasMiddleware`. Must delegate all logic to `AdminService` and return JSON responses via `ResponseHelper` for toggles/deletes.
- [ ] T017 [P] [US2] Create `index.blade.php`, `create.blade.php`, and `edit.blade.php` for Admins in `Modules/Admin/resources/views/admin/admins/`.
- [ ] T018 [US2] Wire up Admin CRUD routes in `Modules/Admin/routes/web.php`.

**Checkpoint**: At this point, User Stories 1 AND 2 should both work independently.

---

## Phase 5: User Story 3 - Role & Permission Management (Priority: P3)

**Goal**: Manage roles and their permissions grouped by category.

**Independent Test**: Create a role, assign permissions from the matrix, edit it, and delete it.

### Implementation for User Story 3

- [ ] T019 [P] [US3] Create `StoreRoleRequest` and `UpdateRoleRequest` in `Modules/Admin/app/Http/Requests/`.
- [ ] T020 [US3] Create `RoleService` in `Modules/Admin/app/Services/RoleService.php` to manage roles and Spatie permission synchronization.
- [ ] T021 [US3] Create `RoleController` in `Modules/Admin/app/Http/Controllers/Admin/RoleController.php` extending `HasMiddleware`. Group permissions by `category` for the UI.
- [ ] T022 [P] [US3] Create `index.blade.php` and `edit.blade.php` for Roles in `Modules/Admin/resources/views/admin/roles/`, including the permission matrix UI.
- [ ] T023 [US3] Wire up Role CRUD routes in `Modules/Admin/routes/web.php`.

**Checkpoint**: All user stories should now be independently functional.

---

## Phase 6: Polish & Cross-Cutting Concerns

**Purpose**: Improvements that affect multiple user stories

- [ ] T024 [P] Add translation strings to `Modules/Admin/resources/lang/en/admin.php` and `ar/admin.php`.
- [ ] T025 Run `quickstart.md` validation.

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies - can start immediately
- **Foundational (Phase 2)**: Depends on Setup completion - BLOCKS all user stories
- **User Stories (Phase 3+)**: All depend on Foundational phase completion
  - Sequential priority order (P1 → P2 → P3) or in parallel if staffed.
- **Polish (Final Phase)**: Depends on all user stories being complete

### Parallel Opportunities

- Creating models, requests, and views across the same story can be done in parallel (`[P]`).
- DTO creation (`T013`) can be parallelized with Request creation (`T014`).
