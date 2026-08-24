# Feature Specification: Admin Module

**Feature Branch**: `002-admin-module`
**Created**: 2026-08-24
**Status**: Draft
**Input**: User description: "respecifiy admin module 002 to handle full admin module against the rules of agents, consitution.md, with reading juicy backend to work similar to as functions and structure but with constitution new rules for laravel 13 we put"

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Admin Authentication (Priority: P1)
As an Admin user, I want to log in to the dashboard securely and manage my profile/session.
**Why this priority**: Required gateway to access the admin panel.
**Independent Test**: Navigate to `/admin/login`, log in, edit profile, and log out.
**Acceptance Scenarios**:
1. **Given** I submit valid credentials, **When** I log in, **Then** I am redirected to the dashboard.
2. **Given** I submit invalid or inactive credentials, **When** I log in, **Then** I am denied access with an error.
3. **Given** I am logged in, **When** I update my profile and image, **Then** the details are saved and the old image is deleted.

### User Story 2 - Admin User Management (Priority: P2)
As a Super Admin, I want to create, edit, activate/deactivate, and delete other admin accounts.
**Why this priority**: Core operational need for onboarding operators.
**Independent Test**: Log in, navigate to admins list, create admin, toggle active, edit, and delete.
**Acceptance Scenarios**:
1. **Given** I have `Create-admin` permission, **When** I submit a new admin, **Then** the account is created and a Spatie role is assigned.
2. **Given** I have `Edit-admin` permission, **When** I toggle activation, **Then** the `is_active` status flips.
3. **Given** I have `Delete-admin` permission, **When** I delete an admin, **Then** the record and image file are permanently removed.

### User Story 3 - Role & Permission Management (Priority: P3)
As a Super Admin, I want to manage roles and their permissions grouped by category.
**Why this priority**: Essential for fine-grained access control based on the constitution's permission requirements.
**Independent Test**: Create a role, assign permissions from the matrix, edit it, and delete it.
**Acceptance Scenarios**:
1. **Given** I have `Index-role` permission, **When** I view roles, **Then** I see all roles and a permission matrix grouped by the `category` column.
2. **Given** I have `Create-role` permission, **When** I save a new role with permissions, **Then** the role is synced correctly.

### Edge Cases
- Deleting an admin who is currently logged in.
- Editing an admin but leaving the password blank (should preserve old password).
- Lack of permissions should throw a 403 Forbidden.

## Requirements *(mandatory)*

### Functional Requirements

#### Authentication (AdminAuthController)
- **FR-001**: System MUST provide web login/logout via `admin` guard (Session-based).
- **FR-002**: System MUST validate auth requests using FormRequests (e.g. `LoginRequest`, `UpdateProfileRequest`).
- **FR-003**: System MUST provide an edit profile UI for the authenticated admin.
- **FR-004**: System MUST handle image upload via `UploaderHelper` and `Illuminate\Support\Facades\Image` at 70% quality, replacing the old file.

#### Admin CRUD (AdminController)
- **FR-005**: System MUST provide CRUD for admins via `AdminController` extending Laravel 13's `HasMiddleware`.
- **FR-006**: System MUST use `AdminService` for all business logic (findAll, findById, save, update, activate, delete).
- **FR-007**: System MUST map HTTP data to typed PHP 8.5 `AdminDto` using constructor property promotion.
- **FR-008**: System MUST validate input strictly via `StoreAdminRequest` and `UpdateAdminRequest`. No inline validation.
- **FR-009**: System MUST enforce authorization via Spatie permissions mapping to Petora's AdminPolicy and gates.

#### Roles & Permissions (RoleController)
- **FR-010**: System MUST provide Role CRUD via `RoleController` (HasMiddleware).
- **FR-011**: System MUST use `RoleService` to manage roles and `Spatie\Permission` synchronization.
- **FR-012**: System MUST retrieve permissions grouped by the custom `category` column for the Blade UI.
- **FR-013**: System MUST seed 46 permissions across all 12 Petora modules with `category` and `display` columns.

#### Model & Database
- **FR-014**: System MUST define `Admin` model implementing `HasRoles`, `HasApiTokens`, `LogsActivity`.
- **FR-015**: System MUST define `is_active`, `image` accessor, and `serializeDate` on the model.
- **FR-016**: System MUST create migration for `admins` table and seed a default Super Admin.

#### UI & Views
- **FR-017**: System MUST use `common::layouts.master` for all views except login.
- **FR-018**: System MUST utilize `x-common::` components (alerts, modals).
- **FR-019**: System MUST provide localized text via `resources/lang/{en,ar}/admin.php`.

### Key Entities
- **Admin**: The operator model (id, name, email, password, phone, image, is_active).
- **Role**: Spatie role model.
- **Permission**: Spatie permission model enhanced with `category` and `display`.

## Success Criteria *(mandatory)*

### Measurable Outcomes
- **SC-001**: Admin auth, CRUD, and role management are fully functional within the dashboard UI.
- **SC-002**: Controllers contain strictly zero business logic (all delegated to Services/DTOs).
- **SC-003**: All routes are protected by Laravel 13 `HasMiddleware` checks tied to Spatie roles.
- **SC-004**: No tests are created, adhering to the "No Tests Required" constitution rule.
- **SC-005**: File uploads compress images to 70% quality using the Laravel 13 Image facade.

## Assumptions
- The `Common` module (including master layouts, `UploaderHelper`) is already available in the workspace.
- The `admin` auth guard is configured for session driver in `config/auth.php`.
- Permissions grouping is handled purely via the database `category` column as per the constitution.
