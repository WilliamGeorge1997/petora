# Data Model: Clinic Module

## Entities

### Clinic

Table: `clinics`

| Column | Type | Modifiers | Description |
|--------|------|-----------|-------------|
| `id` | `unsignedBigInteger` | `auto_increment`, `primary_key` | Primary identifier |
| `title` | `json` | | Bilingual name of the clinic (ar/en) |
| `description` | `json` | | Bilingual description of the clinic (ar/en) |
| `image` | `string` | `nullable` | Path to the uploaded clinic image |
| `address` | `json` | | Bilingual physical address of the clinic (ar/en) |
| `phone` | `string` | | Contact phone number |
| `lat` | `string` | | Latitude coordinate |
| `long` | `string` | | Longitude coordinate |
| `is_active` | `boolean` | `default(true)` | Active state toggle |
| `created_at` | `timestamp` | | Creation timestamp |
| `updated_at` | `timestamp` | | Update timestamp |

## Relationships

- The `Clinic` module is a standalone entity.
- Unlike `Store`, it does NOT have a `company_id`.

## Validation Rules

- `title_en`, `title_ar`: Required, string, max 255.
- `description_en`, `description_ar`: Required, string.
- `address_en`, `address_ar`: Required, string.
- `phone`: Required, string.
- `lat`, `long`: Required, string.
- `image`: Nullable, image format (jpeg, png, jpg, gif, svg), max 2048kb.
- `is_active`: Nullable, boolean.

## State Transitions

- Active/Inactive toggle via `$clinic->is_active = !$clinic->is_active;`
