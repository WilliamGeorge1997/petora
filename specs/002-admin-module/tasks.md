# Tasks: Admin Module

**Feature**: `002-admin-module`
**Date**: 2026-08-24
**Spec**: [spec.md](./spec.md) | **Plan**: [plan.md](./plan.md) | **Data Model**: [data-model.md](./data-model.md)
**Total Tasks**: 46

> No tests are generated — Constitution Principle V: Tests not required at this stage.

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Patch the one broken config reference and prepare directories that artisan commands will not auto-create.

- [ ] T001 Update `config/auth.php` providers.admins.model from `Modules\Admin\Entities\Admin` to `Modules\Admin\app\Models\Admin`
- [ ] T002 Create `Modules/Admin/database/migrations/` directory placeholder (artisan will write here)
- [ ] T003 Create `Modules/Admin/database/seeders/` directory placeholder (artisan will write here)

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Database, Admin model, and DTO — every other phase depends on these.

> ⚠️ CRITICAL: No user story work can begin until this phase is complete.

- [ ] T004 Run `php artisan module:make-migration create_admins_table Admin --no-interaction` then fill migration: columns `id, name(string), email(string unique), password(string), phone(string), is_active(boolean default true), image(string nullable), rememberToken(), timestamps()` in `Modules/Admin/database/migrations/xxxx_create_admins_table.php`
- [ ] T005 Run `php artisan migrate` to apply the admins table migration
- [ ] T006 Run `php artisan module:make-model Admin Admin --no-interaction` then implement full model body in `Modules/Admin/app/Models/Admin.php`:
  - implements `HasApiTokens` (Laravel\Sanctum), `HasRoles` (Spatie), `LogsActivity` (Spatie\Activitylog), `HasFactory`
  - `$fillable = ['name','email','password','phone','image','is_active']`
  - `$hidden = ['password','remember_token']`
  - static activitylog properties: `$logName='Admin'`, `$logAttributes=['*']`, `$logOnlyDirty=true`, `$submitEmptyLogs=false`
  - `serializeDate()` returning `Y-m-d h:i A`
  - `scopeActive($query)` filtering `is_active = true`
  - `getImageAttribute($value)` returning `asset('uploads/admin/'.$value)` or null
- [ ] T007 Run `php artisan module:make-migration create_admins_table Admin --no-interaction` — verify `Modules/Admin/app/Models/Admin.php` namespace is `Modules\Admin\app\Models` (fix if artisan uses wrong namespace)
- [ ] T008 [P] Run `php artisan module:make-seeder AdminDatabaseSeeder Admin --no-interaction` then implement full seeder body in `Modules/Admin/database/seeders/AdminDatabaseSeeder.php`:
  - `adminCreation()`: creates default admin (`admin@admin.com`, `123456789` bcrypt-hashed)
  - `permissionCreation()`: seeds all 46 permissions across 12 modules (Admin/Role/Client/Company/Store/Clinic/Driver/Category/Product/Order/Coupon/Community) with `guard_name='admin'`, `category`, and `display` columns
  - `roleCreation()`: creates `Super Admin` role (`guard_name='admin'`) and syncs ALL permissions to it
  - `run()`: calls all three in order and assigns Super Admin role to the default admin
- [ ] T009 Register `AdminDatabaseSeeder` in `database/seeders/DatabaseSeeder.php` `run()` method: `$this->call([AdminDatabaseSeeder::class])`
- [ ] T010 Run `php artisan db:seed` and verify: Admins count=1, Permissions count=46, Roles count=1
- [ ] T011 [P] Run `php artisan module:make-class DTOs/AdminDto Admin --no-interaction` (or manually create) then implement `Modules/Admin/app/DTOs/AdminDto.php`:
  - PHP 8.5 constructor property promotion
  - Properties: `string $name`, `string $email`, `?string $password` (bcrypt when present), `string $phone`, `?UploadedFile $image`, `bool $is_active`, `string $role`
  - `toArray(): array` method — excludes null password and null image keys

**Checkpoint**: `admins` table exists, Admin model loads, 46 permissions seeded, AdminDto compiles.

---

## Phase 3: User Story 1 — Admin Authentication (Priority: P1) ← MVP

**Goal**: Session-based login, logout, and profile management for admin users.

**Independent Test**: Visit `/admin/login` → log in as `admin@admin.com` / `123456789` → dashboard loads → edit profile → logout → redirected to login.

### Implementation for User Story 1

- [ ] T012 [US1] Run `php artisan module:make-request LoginRequest Admin --no-interaction` then implement validation rules in `Modules/Admin/app/Http/Requests/LoginRequest.php`:
  - `authorize()` returns `true`
  - `rules()`: `email` = required|email, `password` = required|min:6
- [ ] T013 [P] [US1] Run `php artisan module:make-request UpdateProfileRequest Admin --no-interaction` then implement in `Modules/Admin/app/Http/Requests/UpdateProfileRequest.php`:
  - `authorize()` returns `true`
  - `rules()`: name=required|string|max:255, email=required|email|unique:admins,email,{Auth::id()}, phone=required|string, password=nullable|string|min:8|confirmed, image=nullable|image|max:2048
- [ ] T014 [US1] Create `Modules/Admin/app/Http/Controllers/Admin/` directory via `php artisan module:make-controller Admin/AdminAuthController Admin --no-interaction` then implement `AdminAuthController` in `Modules/Admin/app/Http/Controllers/Admin/AdminAuthController.php`:
  - uses `UploaderHelper` trait from `Modules\Common\app\Helpers\UploaderHelper`
  - `showLoginForm()`: returns view `admin::admin.login` — guarded by `guest:admin` via HasMiddleware
  - `login(LoginRequest $request)`: calls `Auth::guard('admin')->attempt(['email'=>$request->email,'password'=>$request->password,'is_active'=>true], $request->remember)` — checks is_active FIRST, redirects to dashboard on success, redirects back with error on failure
  - `logout(Request $request)`: calls `Auth::guard('admin')->logout()`, redirects to `admin.login` route
  - `editProfile()`: loads admin via `(new AdminService())->findById(Auth::id())`, returns view `admin::admin.edit-profile` — guarded by `auth:admin` via HasMiddleware
  - `updateProfile(UpdateProfileRequest $request)`: loads admin, handles image replacement using `Illuminate\Support\Facades\Image` at quality 70 (deletes old file via `getRawOriginal('image')`), conditionally hashes password, updates admin record, redirects to dashboard
  - Implement `HasMiddleware::middleware()`: `guest:admin` for showLoginForm+login; `auth:admin` for logout+editProfile+updateProfile
- [ ] T015 [US1] Create lang directories `Modules/Admin/resources/lang/en/` and `Modules/Admin/resources/lang/ar/` then create `Modules/Admin/resources/lang/en/admin.php` with keys: `login`, `email`, `password`, `logout`, `dashboard`, `edit_profile`, `update_profile`, `name`, `phone`, `image`, `save`, `active`, `inactive`, `roles`, `permissions`, `admins`, `create`, `edit`, `delete`, `actions`, `confirm_delete`, `role`, `remember_me`
- [ ] T016 [P] [US1] Create `Modules/Admin/resources/lang/ar/admin.php` with Arabic translations for all keys from T015
- [ ] T017 [US1] Register lang loading in `Modules/Admin/app/Providers/AdminServiceProvider.php` `boot()` method: `$this->loadTranslationsFrom(module_path('Admin', 'resources/lang'), 'admin');` — add AFTER existing boot code, do not remove existing content
- [ ] T018 [P] [US1] Update `Modules/Admin/routes/web.php` with auth route group (prefix `admin`, middleware `web`):
  - `GET  /admin/login`          → `AdminAuthController@showLoginForm`  named `admin.login`
  - `POST /admin/login`          → `AdminAuthController@login`          named `admin.login.post`
  - `POST /admin/logout`         → `AdminAuthController@logout`         named `admin.logout`
  - `GET  /admin/edit-profile`   → `AdminAuthController@editProfile`    named `admin.edit-profile`
  - `POST /admin/update-profile` → `AdminAuthController@updateProfile`  named `admin.update-profile`
- [ ] T019 [US1] Create Blade view `Modules/Admin/resources/views/admin/login.blade.php`:
  - Standalone page (NOT extending master layout — login has its own styling from Bootstrap 5 template)
  - Login card with email + password fields, remember-me checkbox, submit button
  - Displays `@error('email')` validation errors
  - Uses `route('admin.login.post')` for form action with CSRF token
  - RTL-aware: includes rtl css conditionally based on `App::getLocale() === 'ar'`
- [ ] T020 [P] [US1] Create Blade view `Modules/Admin/resources/views/admin/dashboard.blade.php`:
  - Extends `common::layouts.master`
  - Shows welcome message with authenticated admin name
  - Basic stats stub (total admins count, total roles count) — data passed from future AdminController@dashboard
- [ ] T021 [P] [US1] Create Blade view `Modules/Admin/resources/views/admin/edit-profile.blade.php`:
  - Extends `common::layouts.master`
  - Form with: name, email, phone, password (optional), password_confirmation, image upload preview
  - Shows current image thumbnail if present
  - Posts to `route('admin.update-profile')` with `enctype="multipart/form-data"`
  - Displays validation errors via `x-common::alert` component

**Checkpoint**: Login/logout works, profile update persists, inactive admin blocked, session guard functional.

---

## Phase 4: User Story 2 — Admin User Management (Priority: P2)

**Goal**: Full CRUD for admin accounts with role assignment and activate toggle.

**Independent Test**: Create a new admin → verify in list → toggle activate → edit → delete → image file removed from `public/uploads/admin/`.

### Implementation for User Story 2

- [ ] T022 [US2] Run `php artisan module:make-service AdminService Admin --no-interaction` (or create manually) then implement full `AdminService` body in `Modules/Admin/app/Services/AdminService.php`:
  - `use UploaderHelper` from `Modules\Common\app\Helpers\UploaderHelper` (trait)
  - `findAll(array $data = [], array $relations = []): mixed` — queries Admin excluding `auth()->id()`, supports search/paginated keys, uses `getCaseCollection($builder, $data)` — default paginated=50
  - `findById(int $id): Admin` — loads with `['roles:name']`, uses `findOrFail()`
  - `findBy(string $key, mixed $value): Collection` — returns `Admin::where($key, $value)->get()`
  - `save(array $data): Admin` — uploads image via `Illuminate\Support\Facades\Image::fromUpload()->quality(70)->storePublicly('uploads/admin','public')`, calls `Admin::create($data)`, assigns role via `$admin->assignRole($data['role'])`, returns `findById($admin->id)`
  - `update(int $id, array $data): Admin` — loads admin, deletes old image via `Storage::delete('public/uploads/admin/'.$admin->getRawOriginal('image'))` if new image present, uploads new image, updates admin, syncs role via `$admin->syncRoles([$data['role']])`, returns updated admin
  - `activate(int $id): void` — toggles `is_active` boolean with save
  - `delete(int $id): void` — deletes image file from disk, hard-deletes admin record
- [ ] T023 [US2] Run `php artisan module:make-request StoreAdminRequest Admin --no-interaction` then implement in `Modules/Admin/app/Http/Requests/StoreAdminRequest.php`:
  - `authorize()` uses `$this->user('admin')->can('Create-admin')`
  - `rules()`: name=required|string|max:255, email=required|email|unique:admins,email, password=required|string|min:8|confirmed, phone=required|string, role=required|string|exists:roles,name, image=nullable|image|max:2048, is_active=nullable|boolean
- [ ] T024 [P] [US2] Run `php artisan module:make-request UpdateAdminRequest Admin --no-interaction` then implement in `Modules/Admin/app/Http/Requests/UpdateAdminRequest.php`:
  - `authorize()` uses `$this->user('admin')->can('Edit-admin')`
  - `rules()`: name=required|string|max:255, email=required|email|unique:admins,email,{$this->route('admin')}, password=nullable|string|min:8|confirmed, phone=required|string, role=required|string|exists:roles,name, image=nullable|image|max:2048, is_active=nullable|boolean
- [ ] T025 [US2] Run `php artisan module:make-policy AdminPolicy Admin --no-interaction` then implement `Modules/Admin/app/Policies/AdminPolicy.php`:
  - `viewAny(Admin $admin)`: returns `$admin->can('Index-admin')`
  - `create(Admin $admin)`: returns `$admin->can('Create-admin')`
  - `update(Admin $admin)`: returns `$admin->can('Edit-admin')`
  - `delete(Admin $admin)`: returns `$admin->can('Delete-admin')`
- [ ] T026 [US2] Register `AdminPolicy` in `Modules/Admin/app/Providers/AdminServiceProvider.php` `boot()` using `Gate::policy(\Modules\Admin\app\Models\Admin::class, \Modules\Admin\app\Policies\AdminPolicy::class)` — add `use Illuminate\Support\Facades\Gate` import
- [ ] T027 [US2] Replace the stub `Modules/Admin/app/Http/Controllers/Admin/AdminController.php` with a full implementation (move existing stub first if needed):
  - implements `HasMiddleware` interface
  - injects `AdminService` via constructor (used in 5+ methods)
  - `static middleware()`: auth:admin + permission:Index-admin (index), permission:Create-admin (create,store), permission:Edit-admin (edit,update,activate), permission:Delete-admin (destroy)
  - `index(Request $request)`: calls `$this->adminService->findAll(['paginated'=>50], ['roles:name'])`, passes roles list from `Role::where('guard_name','admin')->get()`, returns view `admin::admin.admins.index`
  - `create()`: passes roles list, returns view `admin::admin.admins.create`
  - `store(StoreAdminRequest $request)`: builds dto via `AdminDto`, calls `$this->adminService->save($dto->toArray())`, redirects to `admin.admins.index` with success flash
  - `edit(int $id)`: loads admin via service, passes roles list, returns view `admin::admin.admins.edit`
  - `update(UpdateAdminRequest $request, int $id)`: calls `$this->adminService->update($id, (new AdminDto($request))->toArray())`, redirects to `admin.admins.index` with success flash
  - `activate(int $id)`: calls `$this->adminService->activate($id)`, returns JSON `['status'=>true]`
  - `destroy(int $id)`: calls `$this->adminService->delete($id)`, returns JSON `['status'=>true]`
- [ ] T028 [US2] Add admin resource routes to `Modules/Admin/routes/web.php`:
  - `Route::resource('admins', AdminController::class)->names('admin.admins')`
  - `Route::post('admins/{id}/activate', [AdminController::class, 'activate'])->name('admin.admins.activate')`
  - All routes inside `prefix('admin')->middleware(['web','auth:admin'])` group
- [ ] T029 [US2] Add dashboard route to `Modules/Admin/routes/web.php`: `Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware(['web','auth:admin'])`; add `dashboard()` method to AdminController returning view `admin::admin.dashboard` with admin/role counts
- [ ] T030 [US2] Create Blade view `Modules/Admin/resources/views/admin/admins/index.blade.php`:
  - Extends `common::layouts.master`
  - Data table: columns = name, email, phone, image (thumbnail 40px), role badge, is_active toggle button, edit link, delete button
  - `x-common::confirm-delete` for delete action (posts to `route('admin.admins.destroy', $admin)`)
  - Activate toggle calls `route('admin.admins.activate', $admin->id)` via JS fetch, flips badge class on response
  - Pagination links via `$admins->links()`
  - Create button links to `route('admin.admins.create')`
  - `x-common::alert` for session flash messages
- [ ] T031 [P] [US2] Create Blade view `Modules/Admin/resources/views/admin/admins/create.blade.php`:
  - Extends `common::layouts.master`
  - Form fields: name, email, password, password_confirmation, phone, is_active checkbox, role `<select>` populated from `$roles`, image file input
  - Posts to `route('admin.admins.store')` with `enctype="multipart/form-data"` and CSRF
  - Displays `@error()` per field
- [ ] T032 [P] [US2] Create Blade view `Modules/Admin/resources/views/admin/admins/edit.blade.php`:
  - Extends `common::layouts.master`
  - Same fields as create; pre-populates with `$admin` data
  - Password field optional (placeholder: leave blank to keep current)
  - Current image preview with remove option
  - Spoofs PUT via `@method('PUT')`
  - Posts to `route('admin.admins.update', $admin)`

**Checkpoint**: Create/list/edit/activate/delete admin works; image files stored and removed correctly; permissions gate each action.

---

## Phase 5: User Story 3 — Role & Permission Management (Priority: P3)

**Goal**: Create, view, edit, delete Spatie roles with permission matrix grouped by category.

**Independent Test**: Create `Store Manager` role with 3 permissions → verify appears in list → edit permissions → delete role → gone from list.

### Implementation for User Story 3

- [ ] T033 [US3] Create `Modules/Admin/app/Services/RoleService.php` (manually or via make:class):
  - `findAll(): Collection` — `Role::where('guard_name','admin')->orderBy('id','asc')->get(['id','name','created_at'])`
  - `findAllPermission(): Collection` — `Permission::all()->groupBy('category')`
  - `findById(int $id): Role` — `Role::findOrFail($id)`
  - `save(array $data): Role` — `Role::create(['name'=>$data['name'],'guard_name'=>'admin'])`, then `$role->syncPermissions($data['permission'])`, returns role
  - `update(int $id, array $data): Role` — finds role, updates name, calls `syncPermissions($data['permission'])`, returns role
  - `delete(int $id): void` — `Role::findOrFail($id)->delete()`
- [ ] T034 [US3] Run `php artisan module:make-request StoreRoleRequest Admin --no-interaction` then implement in `Modules/Admin/app/Http/Requests/StoreRoleRequest.php`:
  - `authorize()` uses `$this->user('admin')->can('Create-role')`
  - `rules()`: name=required|string|max:255|unique:roles,name, permission=required|array, `permission.*`=integer|exists:permissions,id
- [ ] T035 [P] [US3] Run `php artisan module:make-request UpdateRoleRequest Admin --no-interaction` then implement in `Modules/Admin/app/Http/Requests/UpdateRoleRequest.php`:
  - `authorize()` uses `$this->user('admin')->can('Edit-role')`
  - `rules()`: name=required|string|max:255|unique:roles,name,`{$this->route('role')}`, permission=required|array, `permission.*`=integer|exists:permissions,id
- [ ] T036 [US3] Run `php artisan module:make-controller Admin/RoleController Admin --no-interaction` then implement `Modules/Admin/app/Http/Controllers/Admin/RoleController.php`:
  - implements `HasMiddleware` interface
  - injects `RoleService $roleService` via constructor (used in 5 methods)
  - `static middleware()`: auth:admin + permission:Index-role (index), permission:Create-role (store), permission:Edit-role (edit,update), permission:Delete-role (destroy)
  - `index(Request $request)`: calls `$this->roleService->findAll()` and `$this->roleService->findAllPermission()`, returns view `admin::admin.roles.index` with `$roles` and `$catPermissions`
  - `store(StoreRoleRequest $request)`: calls `$this->roleService->save($request->validated())`, returns JSON `['role'=>$role]`
  - `edit(int $id)`: loads role, loads all permissions grouped, loads `$rolePermissions` = current permission IDs for this role (via `$role->permissions->pluck('id')`), returns view `admin::admin.roles.edit`
  - `update(UpdateRoleRequest $request, int $id)`: calls `$this->roleService->update($id, $request->validated())`, redirects to `admin.roles.index` with success flash
  - `destroy(int $id)`: calls `$this->roleService->delete($id)`, returns JSON `['data'=>'success']`
- [ ] T037 [US3] Add role resource routes to `Modules/Admin/routes/web.php`:
  - `Route::resource('roles', RoleController::class)->names('admin.roles')->only(['index','store','edit','update','destroy'])`
  - Inside same admin auth prefix group
- [ ] T038 [US3] Create Blade view `Modules/Admin/resources/views/admin/roles/index.blade.php`:
  - Extends `common::layouts.master`
  - Left panel: roles data table (name, created_at, permissions count, edit link, delete button)
  - Right panel: inline create-role form (name input + permission matrix grouped by category with checkboxes)
  - Permission matrix: iterate `$catPermissions` — each category as a collapsible group heading; each permission as a labeled checkbox `name="permission[]" value="{{ $permission->id }}"`
  - Delete role calls destroy via JS fetch (JSON response), removes row on success
  - `x-common::alert` for flash messages
- [ ] T039 [P] [US3] Create Blade view `Modules/Admin/resources/views/admin/roles/edit.blade.php`:
  - Extends `common::layouts.master`
  - Role name input pre-filled
  - Full permission matrix — checkboxes pre-checked based on `$rolePermissions` (array of current permission IDs)
  - Posts to `route('admin.roles.update', $role)` with `@method('PUT')`
  - Save button redirects to roles index

**Checkpoint**: Create/edit/delete roles + permission sync works; permission matrix grouped by category; existing role permissions pre-checked on edit.

---

## Phase 6: User Story 4 — Dashboard Landing (Priority: P4)

**Goal**: Simple dashboard home page accessible after login.

**Independent Test**: Log in → redirected to `/admin/dashboard` → page renders without errors.

### Implementation for User Story 4

- [ ] T040 [US4] Verify `admin.dashboard` route exists (added in T029) and AdminController `dashboard()` method is complete — passes `$adminsCount = Admin::count()` and `$rolesCount = Role::where('guard_name','admin')->count()` to view
- [ ] T041 [US4] Finalize Blade view `Modules/Admin/resources/views/admin/dashboard.blade.php` (scaffolded in T020):
  - Extends `common::layouts.master`
  - Welcome card with authenticated admin name and role
  - Stats cards: Total Admins, Total Roles, Total Permissions
  - Quick-links to `/admin/admins` and `/admin/roles`

**Checkpoint**: `/admin/dashboard` loads for authenticated admin; unauthenticated access redirects to login.

---

## Phase 7: Polish & Cross-Cutting Concerns

**Purpose**: RTL support, sidebar nav links, and final validation run.

- [ ] T042 [P] Add Admin sidebar nav entries to `Modules/Common/resources/views/includes/sidebar.blade.php` (or equivalent partial): link to `admin.admins.index` (with `Index-admin` gate check) and `admin.roles.index` (with `Index-role` gate check) — use `@can('Index-admin', \Modules\Admin\app\Models\Admin::class)` directive
- [ ] T043 [P] Verify RTL layout applies correctly: all views with `@if(App::getLocale()==='ar')` conditionally include RTL CSS link from `public/assets/css/` — fix any missing conditionals across all 8 views
- [ ] T044 Run `php artisan route:list --path=admin --except-vendor` and verify all expected routes are registered: `admin.login`, `admin.logout`, `admin.edit-profile`, `admin.update-profile`, `admin.dashboard`, `admin.admins.*`, `admin.admins.activate`, `admin.roles.*`
- [ ] T045 [P] Run quickstart.md Scenario 5 (tinker verification): confirm Permissions=46, Roles=1, Admins=1
- [ ] T046 Run quickstart.md full validation: login → admin CRUD → role CRUD → profile update → logout — fix any issues discovered

---

## Dependencies & Execution Order

### Phase Dependencies

```
Phase 1 (Setup)         → no deps — start immediately
Phase 2 (Foundational)  → depends on Phase 1 — BLOCKS all user story phases
Phase 3 (US1 Auth)      → depends on Phase 2 — MVP deliverable
Phase 4 (US2 Admin CRUD)→ depends on Phase 2 + Phase 3 (AdminService needed)
Phase 5 (US3 Roles)     → depends on Phase 2 only — can run in parallel with Phase 4
Phase 6 (US4 Dashboard) → depends on Phase 4 (AdminController dashboard method)
Phase 7 (Polish)        → depends on all story phases
```

### User Story Dependencies

| Story | Depends On | Notes |
|---|---|---|
| US1 — Auth (P1) | Phase 2 foundation | Independent MVP |
| US2 — Admin CRUD (P2) | Phase 2 + AdminService from US2 itself | AdminController needs AdminService |
| US3 — Roles (P3) | Phase 2 only | Independent of US1/US2 — RoleService is self-contained |
| US4 — Dashboard (P4) | US2 (AdminController) | Dashboard method lives in AdminController |

### Within Each Phase — Execution Order

```
Model → DTO → Service → FormRequest → Policy → Controller → Routes → Views → Lang
```

---

## Parallel Opportunities

### Phase 2 (Foundational)
```
T004+T005 (migration+migrate)  →  T006 (Admin model)  →  T011 (AdminDto) [P with T008]
                                                        →  T008 (Seeder) [P with T011]
```

### Phase 3 (US1 Auth)
```
T012 (LoginRequest) [P with T013]  →  T014 (AdminAuthController)  →  T018 (routes)
T013 (UpdateProfileRequest) [P with T012]                           →  T019 (login view)
T015 (lang en) [P with T016]                                        →  T020 (dashboard view) [P]
T016 (lang ar) [P with T015]                                        →  T021 (edit-profile view) [P]
```

### Phase 4 (US2 Admin CRUD)
```
T022 (AdminService)  →  T023 (StoreAdminRequest) [P with T024]  →  T027 (AdminController)  →  T028+T029 (routes)
                     →  T024 (UpdateAdminRequest) [P with T023]                              →  T030 (index view)
T025 (AdminPolicy)   →  T026 (register policy)                                               →  T031 (create view) [P]
                                                                                              →  T032 (edit view) [P]
```

### Phase 5 (US3 Roles)
```
T033 (RoleService)  →  T034 (StoreRoleRequest) [P with T035]  →  T036 (RoleController)  →  T037 (routes)
                    →  T035 (UpdateRoleRequest) [P with T034]                             →  T038 (index view)
                                                                                          →  T039 (edit view) [P]
```

---

## Implementation Strategy

### MVP First (User Story 1 — Auth Only)

1. Complete **Phase 1** (Setup — 3 tasks)
2. Complete **Phase 2** (Foundation — 8 tasks)
3. Complete **Phase 3** (US1 Auth — 10 tasks)
4. **STOP & VALIDATE**: Login → dashboard → edit profile → logout all work
5. This is the **functional entry point** for the entire admin panel

### Incremental Delivery

```
Phase 1+2  →  Foundation ready
Phase 3    →  Login/logout/profile ← DEMO this
Phase 4    →  Admin CRUD  ← DEMO this
Phase 5    →  Role management ← DEMO this
Phase 6    →  Dashboard stats ← DEMO this
Phase 7    →  Polish + sidebar + RTL
```

---

## Notes

- `[P]` tasks operate on different files — safe to implement concurrently
- `[US?]` label maps each task to its user story for traceability
- All PHP files MUST be created with `php artisan module:make-*` commands first, then filled with implementation
- Do NOT run `vendor/bin/pint`, `npm install`, or write tests
- `getRawOriginal('image')` MUST be used when passing image filename to delete methods — the accessor returns a full URL and would break file deletion
- Route file contains route definitions ONLY — all middleware is declared via `HasMiddleware` on each controller
- The `admin` guard uses session driver — `Auth::guard('admin')` for all authentication operations
- `Permission::all()->groupBy('category')` returns a keyed Collection — iterate with `@foreach($catPermissions as $category => $permissions)` in Blade
