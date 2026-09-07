<!-- Sync Impact Report
Version change: 1.7.0 → 1.8.0 (MINOR)
Modified:
  - Updated Principle XI: Changed Canonical Structure from Company to Store, and added rule for ViewModels
  - Added Principle XII: Route Building and Route Parameter Binding Rule (NON-NEGOTIABLE)
Last Amended: 2026-09-07
-->

# Petora Backend Constitution

## Core Principles

### I. Modular-First Architecture (NON-NEGOTIABLE)

Every feature MUST live inside its own `nwidart/laravel-modules` module under `Modules/`.
No business logic, models, or routes are permitted in the root `app/` directory of the Laravel project.
The root `app/` is reserved strictly for framework-level overrides (e.g., `Exceptions/Handler.php`).

> **nwidart v13 note**: In nwidart/laravel-modules v13, each module contains its own `app/` subdirectory where all PHP classes live (`Services/`, `DTOs/`, `Http/`, `Models/`, `Policies/`). The module namespace is auto-resolved by nwidart — the `app/` subfolder is NOT manually registered in `composer.json`.

**Rationale**: Petora has 12 modules (Admin, Common, Company, Store, Clinic, Driver, Client, Order, Coupon, Category, Product, Community) that MUST remain independently manageable.

### II. Services–DTOs–Controller Layering (NON-NEGOTIABLE)

Every module MUST follow this internal layering:

- **DTOs** (`Modules/{Name}/app/DTOs/`): Encapsulates raw HTTP request data into typed PHP 8.5 objects using constructor property promotion. DTOs MUST expose a `toArray(): array` method. Naming: `{Name}Dto.php` (e.g., `AdminDto.php`).
- **Services** (`Modules/{Name}/app/Services/`): Contains ALL business logic. Controllers MUST NOT contain business logic — they delegate to Services only. Services MUST be injected via constructor DI, not instantiated inline. Naming: `{Name}Service.php`.
- **Controller** (`Modules/{Name}/app/Http/Controllers/`): Thin layer only — receives request, builds DTO, calls Service, returns response. Split into `Api/` (Flutter) and `Admin/` (dashboard).
- **Route** (`Modules/{Name}/routes/`): Separate `api.php` (Flutter API, prefix `/api/`) and `web.php` (Admin panel, prefix `/admin/`).
- **Blades** (`Modules/{Name}/resources/views/`): Admin Panel only. Flutter consumes JSON API exclusively.

**Rationale**: Mirrors the proven Juicy backend structure. Keeps controllers thin, services focused, and every layer independently reasoned about.

### III. Common Module is the Shared Kernel

The `Common` module is the single home for cross-cutting concerns. All other modules MAY depend on `Common`; `Common` MUST NOT depend on any other module.

`Common/app/` contains:
- `Helpers/ResponseHelper.php` — global helper functions (`success()`, `failure()`, `getCaseCollection()`, `getSetting()`)
- `Helpers/UploaderHelper.php` — PHP trait for image/file uploads using Laravel's built-in `intervention/image` wrapper
- `Helpers/FCMService.php` — Firebase Cloud Messaging (FCM HTTP v1 API) push notification service
- `resources/views/layouts/` — Blade master layouts for the Admin Panel
- `resources/views/includes/` — reusable Blade partials (sidebar, topbar, alerts, css.blade.php, js.blade.php)

> **SMS**: No SMS provider currently. `SmsService.php` will be added to `Helpers/` in a future phase when a provider is confirmed.

**Rationale**: Prevents code duplication across 12 modules. Naming convention mirrors the Juicy project exactly (`FCMService.php`, `SmsService.php` inside `Helper/` folder).

### IV. API Response Contract (NON-NEGOTIABLE)

All JSON API responses MUST use two named helpers from `Common/app/Helpers/ResponseHelper.php`:

- `success(bool $status, string $message, $data = null, string $status_string = 'ok')` — pass `true` for success
- `failure(bool $status, string $message, $data = null, string $status_string = 'bad_request')` — pass `false` for failure

> **Param order** mirrors the Juicy `return_msg()` signature exactly: `status (bool)` → `message (string)` → `data` → `status_string`

Both helpers MUST return the same envelope shape:

```json
{
  "status": true,
  "message": "Human-readable message",
  "data": {}
}
```

HTTP status codes MUST follow the `allStatusCode()` map (200, 201, 422, 401, 403, 404, 500). Eloquent API Resources MUST be used for all `data` payloads — raw Eloquent models MUST NOT be returned directly.

### IVb. Validation in FormRequests Only (NON-NEGOTIABLE)

All request validation MUST be done inside dedicated `FormRequest` classes in `Modules/{Name}/app/Http/Requests/`. Inline validation inside Controllers or Services is strictly forbidden.

- Create with: `php artisan module:make-request {Name}Request {Module} --no-interaction`
- `authorize()` MUST use Laravel Policies/Gates for dashboard, and `spatie/laravel-permission` for API guards
- Failed validation automatically returns HTTP 422 — no manual handling needed

### V. No Tests Required (Currently)

Tests are **not required** at this stage of development. Do not write, generate, or run tests. This saves implementation time and will be revisited before production release.

- Do NOT create test files unless explicitly requested by the user
- Do NOT run `php artisan test` as part of any implementation task
- Factories may still be created for seeding purposes, but not for test usage

### VI. Bilingual by Design — ar/en (NON-NEGOTIABLE)

The application MUST be fully bilingual in **Arabic (`ar`) and English (`en`)** at two levels:

1. **Admin Dashboard UI**: All Blade views, labels, error messages, and navigation MUST support locale switching between `ar` and `en` using Laravel's `lang/` files inside each module.
2. **JSON Translatable Columns**: Any model attribute that stores user-facing content MUST use `spatie/laravel-translatable` (`HasTranslations` trait + `#[Translatable]` attribute). The column is stored as JSON `{"en": "...", "ar": "..."}`.

Default locale: `en`. Supported locales: `['en', 'ar']`.

Examples of translatable columns: product names, category names, clinic names, coupon descriptions, community post bodies, store names.

**Rationale**: Petora targets a bilingual Middle-Eastern market. Both the admin panel operators and the Flutter app end-users may prefer Arabic.

### VII. Permissions & Activity Logging

**Permissions** MUST use `spatie/laravel-permission` with the following conventions:

- Permission names follow the pattern: `{Action}-{module}` (e.g., `Index-product`, `Create-product`, `Edit-product`, `Delete-product`).
- Every permission MUST have two extra columns on the `permissions` table: `category` (groups permissions by module for the Admin UI, e.g., `'Product'`) and `display` (human-readable action label, e.g., `'Index'`, `'Create'`, `'Edit'`, `'Delete'`).
- Permissions are always seeded with `guard_name = 'admin'`.
- In the Admin UI, permissions are fetched and grouped by `category` column: `Permission::all()->groupBy('category')`.
- The `Super Admin` role receives ALL permissions. Module-scoped roles receive only their relevant category permissions.

**Activity Logging**: All state-changing operations (create, update, delete) on critical models MUST be logged via `spatie/laravel-activitylog` with `$logOnlyDirty = true` and `$submitEmptyLogs = false`.

**File Uploads**: MUST use the `UploaderHelper` trait from `Common/app/Helpers/`. Laravel 13 includes a powerful built-in wrapper for `intervention/image` — you MUST use `Illuminate\Support\Facades\Image`. Images are stored **keeping their original extension** (jpg, png, etc.) with quality reduced to **70%**. Do NOT convert to `.webp`. Stored in `public/uploads/{module}/` matching Juicy project naming.
Uploads MUST be processed using the fluent API like so:
```php
use Illuminate\Support\Facades\Image;

public function uploadImage(\Illuminate\Http\UploadedFile $file, string $module): string
{
    return Image::fromUpload($file)
        ->quality(70)
        ->storePublicly("uploads/{$module}", 'public');
}
```

### VIII. Authentication via Laravel Sanctum (NON-NEGOTIABLE)

All API authentication MUST use **Laravel Sanctum** (included with Laravel — no extra package needed).

**Guard strategy** — one Sanctum guard per user type:

| Guard | Model | Token ability |
|---|---|---|
| `admin` | `Modules\Admin\Models\Admin` | `admin` |
| `client` | `Modules\Client\Models\Client` | `client` |
| `company` | `Modules\Company\Models\Company` | `company` |
| `driver` | `Modules\Driver\Models\Driver` | `driver` |

**Rules:**
- Every authenticatable model MUST implement `HasApiTokens` (Sanctum) + `HasRoles` (spatie/permission).
- Login endpoints issue a token via `$user->createToken('device-name', ['guard-ability'])->plainTextToken`.
- Logout MUST call `$request->user()->currentAccessToken()->delete()` — full token revocation.
- Protected routes use `auth:sanctum` middleware scoped to the correct guard.
- Tokens MUST be returned in the `data` key of the `success()` response on login.
- Token expiry is optional for now — revisit when the app goes to production.

**Rationale**: Sanctum is first-party, already included, supports multiple guards naturally, and allows instant token revocation (critical for driver/client apps). Chosen over JWT to avoid a third-party dependency and its blacklist complexity.

### IX. Engineering Standards (NON-NEGOTIABLE)

All code MUST follow these software engineering principles without exception:

#### General Principles

- **SOLID**
  - *Single Responsibility*: one class = one job. A Service handles business logic, a Controller handles HTTP, a DTO handles data shaping — never mix
  - *Open/Closed*: classes open for extension, closed for modification — use abstract classes, interfaces, traits
  - *Liskov Substitution*: subclasses must be substitutable for their parent without breaking behaviour
  - *Interface Segregation*: prefer small, focused interfaces over large general ones
  - *Dependency Inversion*: depend on abstractions not concretions — inject dependencies via constructor

- **DRY** (Don't Repeat Yourself): any logic that appears more than once MUST be extracted to a Service, Helper, or Blade component

- **KISS** (Keep It Simple, Stupid): always choose the simplest solution that correctly solves the problem — no premature abstraction, no over-engineering

- **YAGNI** (You Aren't Gonna Need It): do not implement features, columns, or methods that are not required right now — add them when the need is confirmed

- **Separation of Concerns (SoC)**: HTTP layer (Controller) MUST NOT know about database details; Service layer MUST NOT know about HTTP request structure; DTO bridges the two

- **Law of Demeter (LoD)**: a method should only call methods on its own object, its direct dependencies, or objects it creates — avoid chaining through unrelated objects (`$order->client->address->city` is a violation)

- **Composition over Inheritance**: prefer traits and composition to deep inheritance chains — Eloquent models should use traits (`HasRoles`, `HasApiTokens`, `LogsActivity`) not extend custom base models unless truly necessary

- **Fail Fast**: validate input and check preconditions at the earliest possible point — throw exceptions or return `failure()` immediately rather than proceeding with invalid state

- **Guard Clauses / Early Return**: avoid deep nesting by returning early for edge cases at the top of a method:
  ```php
  if (!$model) {
      return failure(false, 'Not found', null, 'not_found');
  }
  // happy path continues here unindented
  ```

- **Explicit over Implicit**: be explicit in what code does — avoid magic, hidden side-effects, or context-dependent behaviour that surprises the reader

- **Principle of Least Astonishment**: code should do exactly what its name implies — a method named `getActiveProducts()` must never delete or update anything

- **Boy Scout Rule**: always leave code cleaner than you found it — fix a bad variable name, remove dead code, or improve a comment whenever you touch a file

- **Single Source of Truth (SSOT)**: every piece of data or configuration has exactly one canonical source — never duplicate config values or hardcode values that already exist in the DB or `.env`

- **No Magic Numbers or Strings**: all hardcoded values MUST be extracted to named constants or config values:
  ```php
  // ❌ Wrong
  if ($user->role_id === 3) { }
  // ✅ Correct
  if ($user->hasRole(RoleEnum::SuperAdmin->value)) { }
  ```

- **Descriptive Naming**: variable, method, and class names MUST communicate intent fully — no abbreviations, no single-letter variables outside loops:
  ```php
  // ❌ Wrong
  $u = User::find($id); $u->upd($d);
  // ✅ Correct
  $client = Client::findOrFail($clientId); $client->update($clientData);
  ```

#### Laravel-Specific Best Practices

- **N+1 Query Prevention**: always eager-load relationships when iterating. Use `with()` / `load()`. Never lazy-load inside loops
- **Database Transactions**: wrap all multi-step write operations in `DB::transaction()` — if any step fails, all changes roll back
- **Chunking large datasets**: never load all records into memory — use `chunk()` or `cursor()` for large collections
- **Eloquent-first**: use Eloquent and Query Builder — raw `DB::statement()` queries are forbidden unless absolutely unavoidable
- **Repository pattern**: NOT used — the Service class IS the repository. Direct Eloquent calls happen inside Services only, never in Controllers
- **No logic in Blade views**: views are for display only — all data preparation happens in the Controller or via View Composers
- **Namespace Imports**: All classes MUST be imported at the top of the file via the `use` statement. Inline namespaces (e.g., `\App\Models\User::find(1)`) are strictly forbidden.
- **Service Class Injection**: 
  - If a Service is used in **more than 1 function** in a class, inject it via Constructor Dependency Injection.
  - If a Service is used in **only 1 function**, instantiate it inline inside that function: `(new ProductService())->findAll();`

#### Agent-Specific Rules

- Always read the **official Laravel documentation** before implementing any feature — never assume or guess API behaviour
- When uncertain about what belongs in the backend vs what belongs in the Flutter frontend, **ask the user before proceeding**
- Consult **skills** (`.agents/skills/`) and **AGENTS.md** rules before every task — they override general knowledge
- When uncertain about Flutter/backend split, **ask the user before proceeding**

### X. Dashboard Authorization via Policies & Gates (NON-NEGOTIABLE)

Admin Panel (Blade dashboard) authorization MUST use **Laravel Policies and Gates**, not raw permission string checks in controllers.

- Create a Policy per model: `php artisan module:make-policy {Name}Policy {Module} --no-interaction`
- Policies delegate to `spatie/laravel-permission` internally (e.g., `$user->can('Index-product')`)
- Use `$this->authorize('index', Product::class)` in Admin controllers
- Register policies in the module's `ServiceProvider` via `Gate::policy()`
- API controllers may use `$request->user()->can()` directly without a Policy class if simpler

### XI. The Store Module as the Canonical Structure (NON-NEGOTIABLE)

Any new module created MUST strictly mirror the file structure, conventions, and implementation patterns of the **Store** module. It serves as the locked blueprint for all future module development.

Specifically, when creating a new module, you MUST replicate the presence and structure of the following files:
1. **Model** (`Modules/{Name}/app/Models/`)
2. **Migration** (`Modules/{Name}/database/migrations/`)
3. **Controllers** (`Modules/{Name}/app/Http/Controllers/`) - Both Admin and Api if applicable.
4. **Service** (`Modules/{Name}/app/Services/`)
5. **DTO** (`Modules/{Name}/app/DTOs/`)
6. **Custom Request (FormRequest)** (`Modules/{Name}/app/Http/Requests/`)
7. **Blade Views** (`Modules/{Name}/resources/views/{name}/`): MUST have exactly 3 core views: `index.blade.php`, `edit.blade.php`, and `create.blade.php`.
8. **Translations** (`Modules/{Name}/lang/en/general.php` and `Modules/{Name}/lang/ar/general.php`): Translation files and their exact locations must be maintained.
9. **ServiceProvider** (`Modules/{Name}/app/Providers/{Name}ServiceProvider.php`): MUST manually register and load the translation files according to the nwidart v13 language docs pattern.
10. **ViewModels** (`Modules/{Name}/app/ViewModels/`): A ViewModel should ONLY be applied if the current module needs to view/access a model from another module. Do not create ViewModels by default otherwise.

### XII. Route Building & Route Parameter Binding (NON-NEGOTIABLE)

Every route definition and associated controller method signature MUST adhere to this explicit design decision tree:

1. **Prefer Resource Routing**: Always prefer `Route::resource` or `Route::apiResource` where applicable.
2. **URL & Route Parameter Casing Conventions**:
   - URI path segments MUST be **`kebab-case`** and plural (e.g., `/order-methods`, `/clinic-services`, `/pet-types`).
   - Route parameters MUST ALWAYS be **`snake_case`** (e.g., `{order_method}`, `{clinic_service}`, `{schedule_id}`). NEVER use `camelCase` (e.g., avoid `{clinicService}`).
3. **Explicit / Custom Routes & Parameter Binding**:
   - **Eager Loading Relations:** If relationships WILL be eager-loaded inside the route/action (e.g., `$service->findById($schedule_id, ['relations'])`), do **NOT** use implicit Route Model Binding (`Model $model`). Route Model Binding performs an initial bare query without relations, resulting in duplicate database queries when the relation is subsequently loaded. Instead, accept only the ID in the route definition using `{model_name}_id` (e.g., `{schedule_id}`, `{clinic_id}`) and method signature (`int $schedule_id`), then query the model with its eager-loaded relations in the Service. Avoid using generic `{id}` in nested/custom routes.
   - **No Relations Needed:** If NO relationships need to be loaded (e.g., simple updates, deletions, or status activations), use **Route Model Binding** named `{model_name}` in the route (e.g., `{clinic_service}`, `{schedule}`) and typed `Model $model` in the method directly for cleaner, simpler, and standard syntax.
4. **Top-Level Route Imports**: All controller classes referenced in route files MUST be imported using `use` statements at the top of the file. Inline fully-qualified class names (FQCN) in route files are strictly forbidden.

---

## Technology Stack & Dependencies

| Layer | Technology | Version |
|---|---|---|
| Language | PHP | ^8.5 |
| Framework | Laravel | ^13.17 |
| Module System | nwidart/laravel-modules | ^13.0 |
| Authentication | Laravel Sanctum (API tokens) | included |
| Permissions | spatie/laravel-permission | ^8.3 |
| Translations | spatie/laravel-translatable | ^6.14 |
| Activity Log | spatie/laravel-activitylog | ^5.1 |
| Image Processing | intervention/image (via Laravel built-in wrapper) | ^4.3 |
| Push Notifications | Firebase FCM (via HTTP v1 API) | — |
| Database | MySQL / MariaDB | — |
| Frontend Consumer | Flutter (Petora App) | — |
| WebSocket (future) | Laravel Reverb OR Pusher — TBD | — |

**Package API rule**: Before using any package API, run `composer show <vendor/package>` to confirm the installed version. Do NOT assume API signatures.

---

## Module Architecture

### Module Directory Structure (Canonical)

Every module MUST follow the **nwidart/laravel-modules v13** canonical structure. All PHP classes live under the module's own `app/` subdirectory. The namespace is resolved automatically by nwidart — do NOT add it to the root `composer.json`.

All files MUST be created using `php artisan module:make-*` commands. Do NOT create module files manually.

```
Modules/{Name}/
├── app/
│   ├── DTOs/
│   │   └── {Name}Dto.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/        <- Flutter API controllers
│   │   │   └── Admin/      <- Admin Panel Blade controllers
│   │   ├── Middleware/
│   │   └── Requests/       <- FormRequest validation classes
│   ├── Models/
│   ├── Policies/           <- Laravel Policies (dashboard authorization)
│   ├── Providers/
│   │   ├── {Name}ServiceProvider.php
│   │   └── RouteServiceProvider.php
│   └── Services/
│       └── {Name}Service.php
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── lang/               <- Loaded per nwidart docs (NOT autoloaded)
│   │   ├── en/             <- See: laravelmodules.com/docs/13/advanced/languages
│   │   └── ar/
│   └── views/
│       └── {name}/         <- CRUD blades for admin
└── routes/
    ├── api.php             <- prefix: /api/{module}/
    └── web.php             <- prefix: /admin/{module}/
```

> **Lang loading**: Module lang files are NOT autoloaded. Follow the nwidart v13 language docs at https://laravelmodules.com/docs/13/advanced/languages to register translations in the ServiceProvider.

> **Common module extras**: `app/Helpers/` (ResponseHelper, UploaderHelper, FCMService) and `resources/views/layouts/`, `resources/views/includes/`, `resources/views/components/` (reusable Blade components: modal, alert, confirm-delete, etc.).

### Registered Modules (in load order)

1. **Common** — Shared kernel (load first)
2. **Admin** — Admin authentication & user management
3. **Client** — Mobile app users (Flutter consumers)
4. **Company** — Parent entity that owns and manages one or more Stores
5. **Store** — Store management (a Store belongs to a Company)
6. **Clinic** — Veterinary clinic management
7. **Driver** — Delivery driver management
8. **Category** — Pet & product categories (translatable)
9. **Product** — Product catalog (translatable, belongs to Store via Company)
10. **Order** — Order lifecycle management
11. **Coupon** — Discount & coupon system
12. **Community** — Social/community features

> **Company → Store Hierarchy**: A `Company` is the top-level business entity (e.g., a pet brand or retailer). A `Company` hasMany `Stores`. This mirrors real-world business structures (a company may have multiple store branches) and allows future features like company-level analytics, multi-store coupons, and company admin roles — without any breaking schema changes.

---

## Infrastructure & Constraints

### Admin Panel
Server-rendered Blade only (no SPA). Views live under `Modules/{Name}/resources/views/{name}/`. Routes are under `/admin/` prefix using the `admin` Sanctum guard.

**No npm / No Vite**: The admin panel does NOT use npm or Vite. All CSS, JS, and vendor assets are placed directly in the `public/` directory and loaded as static files. In the Common module, create:
- `resources/views/includes/css.blade.php` — includes all `<link>` tags
- `resources/views/includes/js.blade.php` — includes all `<script>` tags
- Both are included in `resources/views/layouts/master.blade.php`

**Reusable Blade Components**: Any UI element repeated across multiple modules (e.g., modals, alerts, confirm dialogs, delete buttons, status badges, pagination) MUST be created as a Blade component in `Modules/Common/resources/views/components/`. These are referenced in any module's view using the `x-common::` prefix:

```blade
<x-common::modal id="deleteModal" title="Confirm Delete" />
<x-common::alert type="success" :message="session('success')" />
<x-common::confirm-delete :action="route('admin.products.destroy', $product)" />
```

Do NOT duplicate modal/alert HTML across module views — always extract to a Common component instead.

### File Storage
All media stored on **local disk** under `public/uploads/{module}/`. Folder naming matches the Juicy project (e.g., `public/uploads/admin/`, `public/uploads/product/`, `public/uploads/store/`). The `UploaderHelper` trait handles all uploads. API Resources return absolute URLs: `asset('uploads/{module}/{filename}')`.

### Database
**MySQL / MariaDB**. Translatable columns MUST use the `json` column type (never `text`). JSON is natively supported and efficient.

### Push Notifications (FCM)
Push notifications via **Firebase FCM HTTP v1 API**. All logic in `Modules/Common/app/Helpers/FCMService.php`. No other notification channel at this time.

### WebSocket (Future Decision Pending)
Real-time features (e.g., order status updates, driver tracking) may require WebSockets. The choice between **Laravel Reverb** (first-party, self-hosted) and **Pusher** (third-party, managed) is **not yet decided**. Do NOT implement any WebSocket logic until this is resolved with the user.

### Payments
No payment gateway currently. Orders are Cash on Delivery (COD) only. A payment module will be added in a future phase.

### Soft Deletes
**Hard delete only.** `SoftDeletes` MUST NOT be added to any model unless explicitly approved per case.

---

## API & Response Contract

- All Flutter-facing routes: `GET|POST /api/{module}/{resource}`
- Authentication: Sanctum tokens via `Authorization: Bearer {token}` header
- Pagination: `getCaseCollection($builder, $data)` — supports `?paginated=20`
- Image URLs: absolute URLs via `asset('uploads/{module}/{filename}')` in API Resources
- Success: `success(true, string $message, $data)` — envelope key is `message` not `msg`
- Failure: `failure(false, string $message, $data, $status_string)`
- Param order: `status (bool)` → `message` → `data` → `status_string`

---

## Coding Conventions & Standards

### Date & Time Format
All Eloquent models MUST override `serializeDate()` to return dates in the format: **`Y-m-d h:i A`** (e.g., `2026-08-23 11:30 AM`).

```php
protected function serializeDate(\DateTimeInterface $date): string
{
    return $date->format('Y-m-d h:i A');
}
```

This applies to all timestamps (`created_at`, `updated_at`, and any custom date columns). The Flutter app parses this exact format.

### Model Naming & Relationship Conventions

- **Table names**: `snake_case` plural (e.g., `pet_products`, `store_clinics`)
- **Model names**: `PascalCase` singular (e.g., `PetProduct`, `StoreClinic`)
- **Foreign keys**: `{singular_model}_id` (e.g., `company_id`, `store_id`, `client_id`)
- **Pivot tables**: alphabetical order, both singular (e.g., `category_product`, `clinic_driver`)
- **Primary keys**: always `id` (unsigned big integer, auto-increment)
- **Guard columns**: always `is_active` (boolean, default `true`)
- **Mass assignment**: always use `$fillable = [...]` on every model. Never use `$guarded`.
- Relationships MUST be defined on both sides (e.g., if `Store` `belongsTo` `Company`, then `Company` `hasMany` `Store`)

### Image Storage Strategy

Two patterns are used depending on the model's image needs — decided per feature during implementation:

**Pattern A — Single image column** (e.g., Store, Clinic, Client, Driver, Admin):
- A single `image` column (`string`, nullable) on the model's own table
- Stored as filename only (e.g., `1234_logo.jpg`)
- The model MUST define an accessor to automatically return the full URL:
  ```php
  public function getImageAttribute($value)
  {
      if ($value != null && $value != '') {
          return asset('uploads/{module}/' . $value);
      }
      return $value;
  }
  ```
- Returned in API Resource directly by accessing `$this->image`
- **CRITICAL**: When deleting the image using `deleteImage()`, you MUST pass the raw filename, not the full URL. Use Eloquent's `getRawOriginal()` to bypass the accessor:
  ```php
  $this->deleteImage($model->getRawOriginal('image'), 'module_name');
  ```

**Pattern B — Separate images table** (e.g., Product, Community Post):
- A dedicated `{model}_images` table (e.g., `product_images`) with columns: `id`, `{model}_id`, `path`, `sort_order`, `created_at`
- The parent model `hasMany` the images model
- API Resource returns an array of full image URLs

The decision (Pattern A or B) for each model MUST be recorded in its module spec.

### Active/Inactive State Pattern

All models that require enable/disable functionality MUST use:
- A boolean column `is_active` (default `true`) on the migration
- A `scopeActive($query)` scope on the model: `return $query->where('is_active', true);`
- No enum `status` columns unless a model has more than 2 states (e.g., Order status)

### Route Naming Conventions

**Admin Panel routes** use `Route::resource()` when all standard CRUD actions are present. The `->names()` call sets the `admin.` prefix on Laravel's auto-generated resource route names:

```php
Route::resource('products', ProductController::class)->names('admin.products');
```

This auto-generates all named routes:

| Method | URI | Name |
|---|---|---|
| GET | `/products` | `admin.products.index` |
| GET | `/products/create` | `admin.products.create` |
| POST | `/products` | `admin.products.store` |
| GET | `/products/{product}` | `admin.products.show` |
| GET | `/products/{product}/edit` | `admin.products.edit` |
| PUT/PATCH | `/products/{product}` | `admin.products.update` |
| DELETE | `/products/{product}` | `admin.products.destroy` |

For partial resource routes (not all CRUD), use `->only([...])` or `->except([...])` on `Route::resource()`.

**API routes** MUST NOT be named. Flutter uses the URL path directly — route names serve no purpose in the API.

### Middleware Convention

All controllers MUST apply middleware using the **Laravel 13 `HasMiddleware` interface** — not the constructor. Route files MUST NOT chain `->middleware()` on any route.

```php
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum'),
            new Middleware('permission:Index-product', only: ['index']),
            new Middleware('permission:Create-product', only: ['create', 'store']),
            new Middleware('permission:Edit-product', only: ['edit', 'update']),
            new Middleware('permission:Delete-product', only: ['destroy']),
        ];
    }
}
```

Route files (`api.php`, `web.php`) contain **route definitions only**.

### OTP / Phone Verification

Phone verification via SMS OTP is a **future feature** — no SMS provider is confirmed yet. When added:
- `SmsService.php` will be placed in `Modules/Common/app/Helpers/` (matching Juicy `Helper/SmsService.php` pattern)
- OTP codes: 6 digits, valid 10 min, stored in cache
- `phone_verified_at` timestamp set on verify
- Flow: `send-otp` → SMS → `verify-otp` → token issued

### User Default Locale

All authenticatable models (Client, Driver, Company) MUST have a `locale` column (`string`, default `'en'`). This stores the user's preferred language and is set on registration/profile update. The `SetLocale` middleware checks the `Accept-Language` header first; if absent, falls back to `auth()->user()->locale`.

### Standard Service Method Contract

Every Service class for a model with full CRUD MUST implement these 7 methods, mirroring the Juicy project's pattern exactly:

```php
class ProductService
{
    use UploaderHelper;

    function findAll(array $data = [], array $relations = []): mixed
    // Returns paginated or full list. Accepts filters, search, sort via $data.
    // Uses getCaseCollection($builder, $data) for pagination.

    function findById(int $id): Model
    // Returns single model with common relations. Uses findOrFail().

    function findBy(string $key, mixed $value): Collection
    // Returns collection filtered by a single column/value pair.

    function save(array $data): Model
    // Creates the model. Handles image upload if present. Returns fresh model via findById().

    function update(int $id, array $data): Model
    // Updates the model. Replaces image if new one uploaded (deletes old file). Returns updated model.

    function activate(int $id): void
    // Toggles is_active boolean. No return value.

    function delete(int $id): void
    // Hard deletes the model. Deletes associated image file from disk.
}
```

**Rules:**
- `findAll()` MUST always accept `$data = []` and `$relations = []` parameters
- `save()` MUST return the freshly loaded model via `findById()` after creation — never return the create result directly
- `update()` MUST delete the old image file from disk before saving a new one
- `activate()` MUST use the toggle pattern: `$model->is_active = !$model->is_active`
- `delete()` MUST clean up all associated files before deleting the record
- Services that don't need all 7 (e.g., read-only) implement only what is required

### Service `findAll()` Filtering Keys

All `findAll()` `$data` arrays support these standard keys:

| Key | Type | Description |
|---|---|---|
| `search` | string | Full-text search on the model's searchable columns |
| `paginated` | int | Items per page. Default for API: 20. Default for dashboard: **50** |
| `sort_by` | string | Column to sort by (default: `id`) |
| `sort_dir` | string | `asc` or `desc` (default: `desc`) |
| `is_active` | bool | Filter by active state |

The `getCaseCollection($builder, $data)` helper handles the `paginated` key automatically.

### Seeder & Migration Strategy

- **Migrations**: Always run `php artisan migrate` directly. DO NOT use `php artisan module:migrate`. Laravel 13 auto-discovers module migrations.
- **Seeders**: 
  - Every module MUST have a `{Name}DatabaseSeeder.php` in `Modules/{Name}/Database/Seeders/`
  - You MUST register each module's seeder inside the main `database/seeders/DatabaseSeeder.php` `run()` method:
    ```php
    use Modules\Common\Database\Seeders\CommonDatabaseSeeder;

    $this->call([
        CommonDatabaseSeeder::class,
    ]);
    ```
  - Always run `php artisan db:seed` directly. DO NOT use `php artisan module:seed`.
  - Seeders are responsible for: initial permission creation, default roles, and required lookup data.
  - Test factories MUST be separate from seeders and MUST NOT be used in production seeders.

### Admin Panel Template

The Admin Panel MUST use the **Bootstrap 5 template** at:
`G:\William\html-laravel-version\Bootstrap5\full-version`

- Layouts ported from `resources/views/layouts/` into `Modules/Common/resources/views/layouts/`
- Partials (sidebar, navbar, breadcrumbs) into `Modules/Common/resources/views/includes/`
- CSS files copied to `public/assets/css/`, JS to `public/assets/js/`, referenced in `css.blade.php` / `js.blade.php`
- RTL CSS available via `css-rtl/` directory — include conditionally based on active locale
- All Admin Blade views MUST extend `common::layouts.master`

### Locale Detection

- `Accept-Language` header is read by `Modules/Common/app/Http/Middleware/SetLocale.php`
- If no header, falls back to `auth()->user()->locale` column (default `en`)
- Applied globally to all `api` and `web` routes
- `App::setLocale()` is called so both lang files and translatable attributes respond correctly

---

## Development Workflow

1. **Before writing any code**: Read `.ai/rules/index.md`, all matching rule files, and check `.agents/skills/` for any skill relevant to the task. Read the official Laravel docs for any feature being implemented.
2. **Read AGENTS.md**: Rules in `AGENTS.md` and `.ai/rules/` are mandatory and override general knowledge.
3. **Module creation**: `php artisan module:make {Name} --no-interaction`
4. **All file creation MUST use nwidart commands only** — never create module PHP files manually:
   - Model: `php artisan module:make-model {Name} {Module} --no-interaction`
   - Controller: `php artisan module:make-controller {Name}Controller {Module} --no-interaction`
   - Request: `php artisan module:make-request {Name}Request {Module} --no-interaction`
   - Migration: `php artisan module:make-migration create_{name}_table {Module} --no-interaction`
   - Seeder: `php artisan module:make-seeder {Name}Seeder {Module} --no-interaction`
   - Policy: `php artisan module:make-policy {Name}Policy {Module} --no-interaction`
   - After creating any file via nwidart: **keep the auto-generated comments inside the file** but do NOT add new inline comments while writing code.
5. **Migrations**: `php artisan module:migrate {Module}`
6. **No Pint**: Do NOT run `vendor/bin/pint`. Code formatting is not enforced by tooling.
7. **No Tests**: Do NOT write or run tests unless explicitly requested.
8. **Never add new top-level directories** without user approval.
9. **Never install Composer or NPM packages** without user approval.
10. **No npm / No Vite**: Do NOT run `npm install`, `npm run build`, or `npm run dev`. Assets are served statically from `public/`.
11. **When stuck on Flutter/backend split**: Ask the user before proceeding — do not make assumptions about what the backend should expose.

---

## Governance

This constitution supersedes all other development practices for the Petora Backend project.

- Any amendment requires a version bump and `LAST_AMENDED_DATE` update.
- MAJOR bump: removing or redefining a NON-NEGOTIABLE principle.
- MINOR bump: adding a new principle or section.
- PATCH bump: clarifications or wording fixes.
- All AI agents MUST read this constitution before writing any code.
- The Boost MCP `record-rule` tool MUST be used to record convention decisions into `.ai/rules/`.

**Version**: 1.7.0 | **Ratified**: 2026-08-23 | **Last Amended**: 2026-08-26
