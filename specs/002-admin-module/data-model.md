# Data Model: 002-admin-module

## 1. Entity: Admin
**Description**: The operator using the dashboard.

**Fields**:
- `id` (bigIncrements, primary key)
- `name` (string, max: 255)
- `email` (string, max: 255, unique)
- `password` (string, bcrypt hash)
- `phone` (string, max: 50)
- `is_active` (boolean, default: true)
- `image` (string, nullable) - Stores the filename only.
- `remember_token` (string, nullable)
- `created_at`, `updated_at` (timestamps)

**Relationships**:
- Belongs to one Spatie `Role` via `model_has_roles` (Spatie permission).

**Accessors & Mutators**:
- `image`: Accessor `getImageAttribute($value)` returns `asset('uploads/admin/'.$value)` or null.

## 2. Entity: Role (Spatie)
**Description**: A group of permissions assigned to an admin.

**Fields**:
- `id` (bigIncrements, primary key)
- `name` (string, max: 255)
- `guard_name` (string, max: 255) - Always `admin` in this module.
- `created_at`, `updated_at` (timestamps)

**Relationships**:
- BelongsToMany `Permission`
- BelongsToMany `Admin` (via model_has_roles)

## 3. Entity: Permission (Spatie)
**Description**: An action a role is allowed to perform.

**Fields (Includes Petora Extensions)**:
- `id` (bigIncrements, primary key)
- `name` (string, max: 255) - E.g. `Create-admin`
- `guard_name` (string, max: 255) - Always `admin`
- `category` (string, max: 255) - Petora extension (e.g. `Admin`, `Product`)
- `display` (string, max: 255) - Petora extension (e.g. `Create`, `Edit`)
- `created_at`, `updated_at` (timestamps)

**Validation Rules (Admin DTO/Requests)**:
- `name`: required, string, max 255
- `email`: required, email, unique
- `password`: required on create, nullable on update, min 8, confirmed
- `phone`: required, string
- `role`: required, string, exists in roles
- `image`: nullable, image, max 2048
- `is_active`: boolean
