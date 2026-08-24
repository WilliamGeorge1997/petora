# Data Model: Admin Module

**Phase**: 1 — Design
**Date**: 2026-08-24

---

## Entity: Admin

**Table**: `admins`
**Guard**: `admin` (session driver)
**Module path**: `Modules/Admin/app/Models/Admin.php`

### Columns

| Column | Type | Constraints | Notes |
|---|---|---|---|
| `id` | `unsignedBigInteger` | PK, auto-increment | — |
| `name` | `string` | required | Admin display name |
| `email` | `string` | unique, required | Used as login identifier |
| `password` | `string` | required | bcrypt-hashed |
| `phone` | `string` | required | Contact number |
| `is_active` | `boolean` | default `true` | Enable/disable login |
| `image` | `string` | nullable | Stored filename only (e.g. `1234_photo.jpg`) |
| `remember_token` | `string(100)` | nullable | Laravel remember-me token |
| `created_at` | `timestamp` | auto | — |
| `updated_at` | `timestamp` | auto | — |

### Traits & Interfaces

| Trait / Interface | Package | Purpose |
|---|---|---|
| `HasApiTokens` | Laravel\Sanctum | Sanctum token support (future API use) |
| `HasRoles` | Spatie\Permission | Role assignment, permission checks |
| `LogsActivity` | Spatie\Activitylog | Audit trail for create/update/delete |
| `HasFactory` | Illuminate\Database\Eloquent | Factory support |

### Model Behaviour

- `$fillable`: `['name', 'email', 'password', 'phone', 'image', 'is_active']`
- `$hidden`: `['password', 'remember_token']`
- `$logName`: `'Admin'`
- `$logAttributes`: `['*']`
- `$logOnlyDirty`: `true`
- `$submitEmptyLogs`: `false`
- `serializeDate()`: returns `Y-m-d h:i A`
- `scopeActive($query)`: `where('is_active', true)`
- `getImageAttribute($value)`: returns `asset('uploads/admin/' . $value)` or `null`

### Relationships

| Relation | Type | Target | Via |
|---|---|---|---|
| Roles | ManyToMany | `Spatie\Permission\Models\Role` | `model_has_roles` pivot (Spatie) |
| Permissions (indirect) | ManyToMany | `Spatie\Permission\Models\Permission` | via Role |

---

## Entity: Role (Spatie — no new table)

**Table**: `roles` (Spatie-managed)
**Guard**: `admin`

### Relevant Columns

| Column | Type | Notes |
|---|---|---|
| `id` | `unsignedBigInteger` | PK |
| `name` | `string` | e.g. `Super Admin` |
| `guard_name` | `string` | Must be `admin` for all Petora roles |
| `created_at` | `timestamp` | auto |
| `updated_at` | `timestamp` | auto |

---

## Entity: Permission (Spatie + Petora extensions — no new table)

**Table**: `permissions` (already migrated with `category` and `display` columns)

### Columns

| Column | Type | Notes |
|---|---|---|
| `id` | `unsignedBigInteger` | PK |
| `name` | `string` | e.g. `Index-admin`, `Create-product` |
| `guard_name` | `string` | Always `admin` |
| `category` | `string` | Groups permissions by module in the UI (e.g. `Admin`, `Product`) |
| `display` | `string` | Human-readable action label (e.g. `Index`, `Create`, `Edit`, `Delete`) |
| `created_at` | `timestamp` | auto |
| `updated_at` | `timestamp` | auto |

---

## Seeded Permissions Matrix

All permissions use `guard_name = 'admin'`. Format: `{display}-{module_lowercase}`.

| Category | Permissions Seeded |
|---|---|
| Admin | Index-admin, Create-admin, Edit-admin, Delete-admin |
| Role | Index-role, Create-role, Edit-role, Delete-role |
| Client | Index-client, Create-client, Edit-client, Delete-client |
| Company | Index-company, Create-company, Edit-company, Delete-company |
| Store | Index-store, Create-store, Edit-store, Delete-store |
| Clinic | Index-clinic, Create-clinic, Edit-clinic, Delete-clinic |
| Driver | Index-driver, Create-driver, Edit-driver, Delete-driver |
| Category | Index-category, Create-category, Edit-category, Delete-category |
| Product | Index-product, Create-product, Edit-product, Delete-product |
| Order | Index-order, Edit-order *(no Create/Delete for orders)* |
| Coupon | Index-coupon, Create-coupon, Edit-coupon, Delete-coupon |
| Community | Index-community, Create-community, Edit-community, Delete-community |

**Total**: 46 permissions

---

## Seeded Roles

| Role Name | Guard | Permissions |
|---|---|---|
| `Super Admin` | `admin` | All 46 permissions |

---

## DTO: AdminDto

**Path**: `Modules/Admin/app/DTOs/AdminDto.php`

```
Properties (constructor property promotion):
  + string $name
  + string $email
  + ?string $password       — bcrypt-hashed when present, null otherwise
  + string $phone
  + ?UploadedFile $image    — file reference, null if not uploaded
  + bool $is_active         — defaults to true
  + string $role            — spatie role name

Methods:
  + toArray(): array        — returns all properties as associative array, 
                              excludes null password and null image
```

---

## Validation Rules

### LoginRequest

| Field | Rules |
|---|---|
| `email` | required, email |
| `password` | required, min:6 |

### UpdateProfileRequest

| Field | Rules |
|---|---|
| `name` | required, string, max:255 |
| `email` | required, email, unique:admins,email,{currentAdminId} |
| `phone` | required, string |
| `password` | nullable, string, min:8, confirmed |
| `image` | nullable, image, max:2048 |

### StoreAdminRequest

| Field | Rules |
|---|---|
| `name` | required, string, max:255 |
| `email` | required, email, unique:admins,email |
| `password` | required, string, min:8, confirmed |
| `phone` | required, string |
| `role` | required, string, exists:roles,name |
| `image` | nullable, image, max:2048 |
| `is_active` | nullable, boolean |

### UpdateAdminRequest

| Field | Rules |
|---|---|
| `name` | required, string, max:255 |
| `email` | required, email, unique:admins,email,{adminId} |
| `password` | nullable, string, min:8, confirmed |
| `phone` | required, string |
| `role` | required, string, exists:roles,name |
| `image` | nullable, image, max:2048 |
| `is_active` | nullable, boolean |

### StoreRoleRequest

| Field | Rules |
|---|---|
| `name` | required, string, max:255, unique:roles,name |
| `permission` | required, array |
| `permission.*` | integer, exists:permissions,id |

### UpdateRoleRequest

| Field | Rules |
|---|---|
| `name` | required, string, max:255, unique:roles,name,{roleId} |
| `permission` | required, array |
| `permission.*` | integer, exists:permissions,id |

---

## State Transitions

### Admin `is_active`

```
active (true) ──[toggle]──► inactive (false)
inactive (false) ──[toggle]──► active (true)
```

Blocked admins cannot log in — login check uses `is_active = true` in credentials array.
