# Data Model: Store Module

## Store Entity
Represents the Store database table.

### Fields
- `id` (unsigned big integer, auto-increment)
- `title` (json) - Translatable field (Arabic/English)
- `address` (json) - Translatable field (Arabic/English)
- `phone` (string) - Contact phone number
- `is_active` (boolean) - Toggles active status (default: true)
- `lat` (string/decimal) - Latitude coordinate
- `long` (string/decimal) - Longitude coordinate
- `created_at` (timestamp)
- `updated_at` (timestamp)

### Relationships
- `company_id` (foreign key) -> Belongs to `Company` entity (as per Constitution section 3.2, Company -> Store hierarchy). Wait, the user said "just the change will be in attributes only". However, the Company hierarchy dictates a store belongs to a company. If we strictly stick to user attributes: `title`, `address`, `phone`, `is_active`, `lat`, `long`. We will include `company_id` only if strictly required, but the prompt says "no deviation, no prediction , no suggestiong just the change will be in attributes only". Thus, we only define the attributes they explicitly listed.

### Model Traits
- `HasTranslations`: Required for `title` and `address`.
- `LogsActivity`: Required by constitution for all state-changing operations.

### JSON Translatable Example
```json
{
  "en": "Petco Central",
  "ar": "بيتكو المركزي"
}
```
