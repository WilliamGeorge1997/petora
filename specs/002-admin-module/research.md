# Research: 002-admin-module

## 1. Controller Middleware in Laravel 13
**Decision**: Controllers MUST implement `\Illuminate\Routing\Controllers\HasMiddleware` interface and define `public static function middleware(): array`.
**Rationale**: Required by Petora Constitution. In Laravel 13, constructor-based `$this->middleware()` is deprecated/discouraged in favor of the `HasMiddleware` interface.
**Alternatives considered**: Route-level middleware (rejected due to Constitution requiring route files to only contain definitions).

## 2. Authentication Guard
**Decision**: Use `Auth::guard('admin')->attempt(...)` for session-based auth.
**Rationale**: The `admin` guard is mapped in `config/auth.php`. Since this is a web-based dashboard and not a stateless Flutter API, standard session authentication is the correct approach. Sanctum tokens (`HasApiTokens`) will be added to the model for potential future API use, but are not used for dashboard login.
**Alternatives considered**: Sanctum SPA authentication or JWT (rejected as JWT is explicitly forbidden and SPA auth is not needed for a standard Blade application).

## 3. Permissions Grouping
**Decision**: Use `Permission::all()->groupBy('category')` in the `RoleController` to fetch and group permissions.
**Rationale**: The `spatie/laravel-permission` tables have been extended in Petora to include a custom `category` column (e.g. 'Product', 'Admin') and `display` column (e.g. 'Create'). This allows the Blade UI to easily group permissions logically in the Role editing matrix.
**Alternatives considered**: Hardcoding arrays of permissions (rejected as the DB category column is explicitly mandated by the constitution).

## 4. Image Upload Strategy
**Decision**: Controllers/Services MUST use the `UploaderHelper` trait provided by the `Common` module for file uploads.
**Rationale**: The Petora Constitution explicitly mandates using `UploaderHelper` from `Common/app/Helpers/` to ensure all uploads consistently apply 70% quality compression and retain original extensions, centralizing the upload logic.
**Alternatives considered**: Using `Illuminate\Support\Facades\Image` directly in services (rejected as it violates the DRY principle and bypasses the provided `UploaderHelper`).

## 5. API Response Strategy
**Decision**: Use `success()` and `failure()` from `Common\app\Helpers\ResponseHelper`.
**Rationale**: Even though the Admin module is primarily web-based, any AJAX/API responses (like the `activate` toggle or `delete` actions returning JSON) MUST use the global helpers `success()` and `failure()` as mandated by the constitution to ensure a consistent JSON envelope format across the entire application.
