# Data Model: Common Module

## Setting

**Table**: `settings`

| Field | Type | Modifiers | Description |
|---|---|---|---|
| `id` | unsignedBigInteger | PK, AI | Primary key |
| `key` | string | unique | Configuration key (e.g., 'tax', 'terms_en') |
| `display` | string | | Human-readable name (Admin UI label) |
| `value` | text | nullable | Configuration value |
| `type` | string | | Input type (text, file, number) |
| `created_at` | timestamp | | |
| `updated_at` | timestamp | | |

**Validation Rules**:
- `key`: required, string, unique (on create)
- `display`: required, string
- `type`: required, string, in:text,file,number,textarea

**Seed Data Required**:
- terms_ar
- terms_en
- privacy_ar
- privacy_en
- about_ar
- about_en
- name_ar
- name_en
- facebook
- twitter
- instagram
- whatsapp
- telegram
- snapchat
- tiktok
- phone
- email
- tax
