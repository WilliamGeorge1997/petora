# Feature Specification: Admin Module

**Feature Branch**: `002-admin-module`
**Created**: 2026-08-24
**Status**: Draft
**Input**: User description: "Build the Admin module containing AdminAuthController, AdminController, RoleController — everything similar to the Juicy application for views, controllers, and the Admin model (previously called entity in Laravel 8). Follow the constitution file for roles, permissions, DTOs, Services, FormRequests, Blade views, and seeders."

---

## User Scenarios & Testing *(mandatory)*

### User Story 1 — Admin Authentication (Priority: P1)

As an Admin user, I want to log in to the dashboard with my email and password so that I can access the admin panel securely. I also want to edit my own profile and log out.

**Why this priority**: Authentication is the gateway to the entire dashboard. No other feature can be accessed or tested without a working login flow.

**Independent Test**: Navigate to `/admin/login`, enter valid credentials for the seeded Super Admin account, and verify the dashboard loads. Confirm inactive accounts are blocked. Verify logout redirects to login.

**Acceptance Scenarios**:

1. **Given** I am a guest, **When** I visit `/admin/login`, **Then** I see the login form view.
2. **Given** I submit valid credentials for an active admin, **When** authentication succeeds, **Then** I am redirected to the dashboard.
3. **Given** I submit credentials for an **inactive** admin, **When** I attempt login, **Then** I am redirected back with an error and am NOT authenticated.
4. **Given** I submit invalid credentials, **When** I attempt login, **Then** I am redirected back with a generic credentials error.
5. **Given** I am authenticated, **When** I visit `/admin/edit-profile`, **Then** I see my current profile data pre-filled.
6. **Given** I submit an updated profile (with an optional new image), **When** saved, **Then** name/email/phone/image are updated; old image file is deleted if replaced.
7. **Given** I am authenticated, **When** I click logout, **Then** my session is terminated and I am redirected to `/admin/login`.

---

### User Story 2 — Admin User Management (Priority: P2)

As a Super Admin, I want to create, view, edit, activate/deactivate, and delete other admin accounts so that I can control who has dashboard access.

**Why this priority**: Admin management is a core operational need — without it, access cannot be delegated and no new operators can be onboarded.

**Independent Test**: Log in as Super Admin, navigate to `/admin/admins`, create a new admin with a role, verify the account appears in the list, toggle its status, edit it, then delete it.

**Acceptance Scenarios**:

1. **Given** I am authenticated with `Index-admin` permission, **When** I visit `/admin/admins`, **Then** I see a paginated list (50/page) of all admin accounts excluding my own.
2. **Given** I am authenticated with `Create-admin` permission, **When** I submit the create form with valid data, **Then** a new admin is created with the assigned role.
3. **Given** I submit the create form with a duplicate email, **When** validation fails, **Then** I see a validation error and no record is created.
4. **Given** I am authenticated with `Edit-admin` permission, **When** I submit the edit form, **Then** data is updated; old image deleted if replaced; role replaced if changed.
5. **Given** I am authenticated with `Edit-admin` permission, **When** I toggle activation for an admin, **Then** their `is_active` state is flipped.
6. **Given** I am authenticated with `Delete-admin` permission, **When** I confirm deletion, **Then** the record and associated image file are permanently removed.
7. **Given** I lack the required permission, **When** I attempt the action, **Then** I receive a 403 Forbidden response.

---

### User Story 3 — Role & Permission Management (Priority: P3)

As a Super Admin, I want to create, view, edit, and delete roles — assigning specific permissions to each — so that I can implement fine-grained access control.

**Why this priority**: Roles and permissions determine what every other admin can do. They must be manageable through the UI.

**Independent Test**: Log in as Super Admin, navigate to `/admin/roles`, create a new role with a subset of permissions, edit it to change permissions, then delete it.

**Acceptance Scenarios**:

1. **Given** I am authenticated with `Index-role` permission, **When** I visit `/admin/roles`, **Then** I see all roles and the full permission list grouped by category.
2. **Given** I am authenticated with `Create-role` permission, **When** I submit a name and selected permissions, **Then** the role is created and permissions are synced.
3. **Given** I submit an empty role name, **When** validation runs, **Then** I receive a validation error.
4. **Given** I am authenticated with `Edit-role` permission, **When** I open the edit page, **Then** current permissions are pre-selected alongside all available permissions.
5. **Given** I submit the edit form with updated permissions, **When** saved, **Then** the role's permissions are fully replaced (synced).
6. **Given** I am authenticated with `Delete-role` permission, **When** I delete a role, **Then** the role and all its permission mappings are removed.

---

### User Story 4 — Dashboard Landing (Priority: P4)

As an authenticated Admin, I want to see a dashboard home page after logging in so that I have a central starting point.

**Independent Test**: Log in as Super Admin and verify `/admin/dashboard` loads without errors.

**Acceptance Scenarios**:

1. **Given** I am authenticated, **When** I visit `/admin/dashboard`, **Then** I see the dashboard view without errors.
2. **Given** I am not authenticated, **When** I visit `/admin/dashboard`, **Then** I am redirected to the login page.

---

### Edge Cases

- What happens when an admin tries to delete their own account? (`findAll()` excludes the authenticated user from the list — action is unreachable by design.)
- What happens if a role being deleted is currently assigned to one or more admins? (Must be guarded or cascade — resolved during implementation.)
- What if no image is uploaded on create? (The `image` column is nullable; the accessor returns `null` gracefully.)
- What if a password field is left blank on profile update? (Blank password must be excluded from the update payload to preserve the existing hash.)
- What happens when an admin with only `Edit-admin` tries to access the index? (403 — middleware maps `Index-admin` to `index` action only.)

---

## Requirements *(mandatory)*

### Functional Requirements

#### Authentication (AdminAuthController)

- **FR-001**: System MUST provide a web login form at `/admin/login` using `guest:admin` middleware, accepting email and password.
- **FR-002**: System MUST block login for admins where `is_active = false` and return a descriptive bilingual error message.
- **FR-003**: System MUST authenticate via the `admin` guard using session-based auth (`Auth::guard('admin')->attempt()`). Sanctum token issuance is out of scope for this module.
- **FR-004**: System MUST provide a logout action that destroys the admin session and redirects to `/admin/login`.
- **FR-005**: System MUST provide an edit-profile page and update action for the authenticated admin (name, email, phone, password, image).
- **FR-006**: System MUST delete the old profile image from disk when a new image is uploaded on profile update.
- **FR-007**: Validation for login MUST use a dedicated `LoginRequest` FormRequest (email required/valid, password required min 6).
- **FR-008**: Validation for profile update MUST use a dedicated `UpdateProfileRequest` FormRequest.

#### Admin CRUD (AdminController)

- **FR-009**: System MUST display a paginated list (50 per page) of all admin accounts excluding the authenticated admin.
- **FR-010**: System MUST allow creating a new admin with: `name` (required), `email` (required, unique), `password` (required, min 8, confirmed), `phone` (required), `role` (required), `image` (optional image file), `is_active` (boolean default true).
- **FR-011**: System MUST assign exactly one Spatie role on create and replace it on update.
- **FR-012**: System MUST allow updating an admin; password is optional — omitted password must not overwrite existing hash.
- **FR-013**: System MUST provide an `activate` action that toggles `is_active` on/off.
- **FR-014**: System MUST hard-delete an admin and remove associated image file from disk.
- **FR-015**: Validation MUST use dedicated `StoreAdminRequest` and `UpdateAdminRequest` FormRequest classes.
- **FR-016**: `Admin` Eloquent model MUST live at `Modules/Admin/app/Models/Admin.php`.
- **FR-017**: `Admin` model MUST implement `HasApiTokens` (Sanctum), `HasRoles` (spatie/permission), `LogsActivity` (spatie/activitylog). JWT is NOT used — Juicy's `JWTSubject` is replaced by Sanctum.
- **FR-018**: `Admin` model MUST use the `admin` guard and authenticate against the `admins` table.
- **FR-019**: `Admin` model MUST define an `image` accessor returning `asset('uploads/admin/{filename}')` or `null`.
- **FR-020**: `Admin` model MUST override `serializeDate()` to return `Y-m-d h:i A` format.
- **FR-021**: `Admin` model MUST define a `scopeActive($query)` local scope.
- **FR-022**: All controller permission checks MUST use the `HasMiddleware` interface (Laravel 13) — NOT constructor `$this->middleware()` calls.
- **FR-023**: System MUST provide an `AdminDto` PHP 8.5 class with constructor property promotion exposing: `name`, `email`, `password` (bcrypt-hashed when present), `phone`, `image`, `is_active`, `role`. MUST expose a `toArray(): array` method.
- **FR-024**: System MUST provide an `AdminService` implementing the 7 standard service methods: `findAll`, `findById`, `findBy`, `save`, `update`, `activate`, `delete`.
- **FR-025**: `AdminService::save()` MUST use `Illuminate\Support\Facades\Image` with `.quality(70)->storePublicly('uploads/admin', 'public')` for image upload.
- **FR-026**: `AdminService::update()` MUST delete the old image using `$model->getRawOriginal('image')` before saving the new file.

#### Role Management (RoleController)

- **FR-027**: System MUST display all Spatie roles and all permissions grouped by `category` on the roles index.
- **FR-028**: System MUST allow creating a role with a name and syncing a set of permission IDs.
- **FR-029**: System MUST allow editing a role's name and fully replacing its permissions.
- **FR-030**: System MUST allow hard-deleting a role by ID.
- **FR-031**: All role/permission operations MUST use `guard_name = 'admin'`.
- **FR-032**: System MUST provide a `RoleService` with: `findAll`, `findAllPermission` (grouped by category), `findById`, `save`, `update`, `delete`.
- **FR-033**: Validation MUST use dedicated `StoreRoleRequest` and `UpdateRoleRequest` FormRequest classes.

#### Database & Seeding

- **FR-034**: System MUST provide a migration creating the `admins` table: `id`, `name` (string), `email` (string unique), `password` (string), `phone` (string), `is_active` (boolean default true), `image` (string nullable), `remember_token`, `timestamps`.
- **FR-035**: System MUST confirm or add a migration for the `category` (string) and `display` (string) columns on the Spatie `permissions` table — these are Petora custom columns required by the constitution.
- **FR-036**: System MUST provide an `AdminDatabaseSeeder` that: creates the default Super Admin account (`admin@admin.com` / `123456789`), seeds all Petora module permissions with `category` and `display`, creates `Super Admin` role (all permissions), assigns `Super Admin` role to the default admin.
- **FR-037**: Permissions to seed MUST cover all 12 Petora modules: Admin (Index/Create/Edit/Delete), Role (Index/Create/Edit/Delete), Client (Index/Create/Edit/Delete), Company (Index/Create/Edit/Delete), Store (Index/Create/Edit/Delete), Clinic (Index/Create/Edit/Delete), Driver (Index/Create/Edit/Delete), Category (Index/Create/Edit/Delete), Product (Index/Create/Edit/Delete), Order (Index/Edit only), Coupon (Index/Create/Edit/Delete), Community (Index/Create/Edit/Delete). All with `guard_name = 'admin'`.

#### Views (Blade)

- **FR-038**: System MUST provide Blade views: `admin::login`, `admin::dashboard`, `admin::edit-profile`, `admin::admins.index`, `admin::admins.create`, `admin::admins.edit`, `admin::roles.index`, `admin::roles.edit`.
- **FR-039**: All views MUST extend `common::layouts.master` and use `x-common::` Blade components for modals, alerts, and confirm-delete dialogs.
- **FR-040**: Admin list view MUST show a data table: name, email, phone, image (thumbnail), role badge, is_active toggle, edit/delete action buttons.
- **FR-041**: Admin create/edit forms MUST include a dropdown of all available Spatie roles.
- **FR-042**: Roles index MUST show a permission matrix grouped by category using checkboxes.
- **FR-043**: All label text MUST use module lang files (`Modules/Admin/resources/lang/en/` and `ar/`) registered per nwidart v13 documentation.
- **FR-044**: RTL layout MUST be applied conditionally based on the active locale using the dashboard template RTL CSS.

#### Routes

- **FR-045**: Auth routes (login GET/POST, logout POST, edit-profile GET, update-profile POST) MUST be in `Modules/Admin/routes/web.php` under the `/admin` prefix.
- **FR-046**: `Route::resource('admins', AdminController::class)->names('admin.admins')` with an additional `activate` route (`POST admin/admins/{id}/activate`).
- **FR-047**: `Route::resource('roles', RoleController::class)->names('admin.roles')->only(['index', 'store', 'edit', 'update', 'destroy'])`.
- **FR-048**: Route files contain route definitions only — no middleware chaining on individual routes.

### Key Entities

- **Admin**: Dashboard operator. Attributes: `id`, `name`, `email`, `password`, `phone`, `image` (stored as filename; URL returned via accessor), `is_active`, `remember_token`, `created_at`, `updated_at`. Belongs to one Spatie Role. Uses the `admin` Sanctum guard.
- **Role** (Spatie): A permission group for admins. Attributes: `id`, `name`, `guard_name`. Has many Permissions.
- **Permission** (Spatie + Petora extension): Spatie permission model with two extra columns: `category` (string — groups by module in the UI, e.g. `'Product'`) and `display` (string — human-readable label, e.g. `'Create'`). All seeded with `guard_name = 'admin'`.

---

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: A Super Admin can log in, manage admin accounts, and log out within 3 navigation steps each.
- **SC-002**: All 7 `AdminService` standard methods are verifiable: create/read/update/activate/delete an admin record, with image files correctly created and removed from `public/uploads/admin/`.
- **SC-003**: All Petora module permissions (12 modules) are seeded and visible in the role permission matrix grouped by category.
- **SC-004**: An admin assigned a role with only `Index-product` permission can access the product list but receives 403 on create/edit/delete actions.
- **SC-005**: All Admin Panel views render without PHP errors in both `en` and `ar` locales, with RTL layout applied for Arabic.
- **SC-006**: No business logic exists inside any Controller method — all data operations are delegated entirely to `AdminService` or `RoleService`.

---

## Assumptions

- The `Common` module is already built and provides: `common::layouts.master`, all `x-common::` Blade components, `UploaderHelper` trait, and Bootstrap 5 template assets in `public/assets/`.
- The `admin` Sanctum guard must be registered in `config/auth.php` — this will be confirmed via `php artisan config:show auth` during implementation.
- The Spatie `permissions` table requires `category` and `display` columns as Petora custom extensions — a migration to add these columns is included in this module if not already present.
- Only `Super Admin` role is seeded at this phase; additional roles are created via the Role Management UI.
- No Flutter-facing (API) routes are part of this module — admin auth is session-based, dashboard-only.
- The `locale` column is NOT required on the `Admin` model (constitution mandates it for Client, Driver, Company — not Admin operators).
- Image storage follows Pattern A: single nullable `image` column on `admins` table, stored as filename only, returned as full URL via Eloquent accessor.
- The dashboard home page (`/admin/dashboard`) at this phase is a simple welcome stub — full statistics and analytics are out of scope.
- The `AdminController` in Petora does NOT include the Juicy-specific order-card/branch-dashboard/statistics functionality — those belong to the Order module.
