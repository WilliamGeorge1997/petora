# Research & Decisions: Store Module

## Technical Context Resolution

Since the constitution heavily restricts the structure of new modules, we rely directly on the existing `Company` module as our template. 
No external API calls, third-party libraries (other than the established ones), or complex architectural designs are necessary.

- **Language/Version**: PHP 8.5
- **Primary Dependencies**: Laravel 13.17, nwidart/laravel-modules 13.0, spatie/laravel-translatable 6.14
- **Project Type**: Laravel Module
- **Testing**: Tests are disabled (per Constitution Principle V)

## Core Design Decisions

### 1. Nwidart Command Usage
- **Decision**: All files will be generated exclusively using `php artisan module:make-*` commands.
- **Rationale**: Strict compliance with Constitution Principle IX and XI. Hand-crafting files is prohibited.
- **Alternatives considered**: None, as this is a strict constitution mandate.

### 2. Translatable JSON Attributes
- **Decision**: `title` and `address` will be stored as `json` column types in the migration, and the `Store` model will implement `HasTranslations` from `spatie/laravel-translatable`.
- **Rationale**: User explicit prompt: "store will have title json, address json". Constitution Principle VI: "Any model attribute that stores user-facing content MUST use spatie/laravel-translatable".
- **Alternatives considered**: Separate translation tables were rejected due to the established JSON column pattern (Principle VI).

### 3. Active Status Toggle
- **Decision**: Implement an `is_active` boolean column with a `scopeActive` in the model and an `activate()` method in `StoreService`.
- **Rationale**: Follows the Active/Inactive State Pattern (Constitution section 4.4).

### 4. Required Files Mapping
We will generate:
1. **Model**: `Store` (with `HasTranslations` and fillables)
2. **Migration**: `create_stores_table` (with `title` json, `address` json, `phone` string, `is_active` boolean, `lat` string/decimal, `long` string/decimal)
3. **Controller**: `StoreController`
4. **Service**: `StoreService` (with 7 standard methods)
5. **DTO**: `StoreDto`
6. **FormRequest**: `StoreRequest`
7. **Blade Views**: `index`, `create`, `edit` inside `resources/views/stores/`
8. **Translations**: `lang/en/general.php` and `lang/ar/general.php`
9. **ServiceProvider**: `StoreServiceProvider.php` (must manually load translations)
10. **Policy**: `StorePolicy` (Dashboard authorization - Principle X)
11. **Seeder**: `StoreDatabaseSeeder` (for permissions - Principle VII)
