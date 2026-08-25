# Phase 1: Data Model

## Entity: Company

**Table**: `companies`

### Fields

| Field Name | Type | Modifiers | Description |
|---|---|---|---|
| `id` | bigInteger | unsigned, primary, auto-increment | Primary key |
| `title` | json | | Bilingual company name (ar/en) |
| `phone` | string | nullable | Contact phone number |
| `address` | json | nullable | Bilingual physical address (ar/en) |
| `is_active` | boolean | default(true) | Used by `scopeActive()` for active toggling |
| `created_at` | timestamp | | |
| `updated_at` | timestamp | | |

### Traits & Behaviors

- `HasTranslations`: on `$translatable = ['title', 'address']`
- `LogsActivity`: `getActivitylogOptions()` configuring dirty logging.
- `scopeActive`: Filter `is_active = true`.

### Validations (FormRequest)

- `title.en`: required, string
- `title.ar`: required, string
- `phone`: nullable, string
- `address.en`: nullable, string
- `address.ar`: nullable, string
