# Quickstart & Validation Guide: Store Module

This guide details how to validate that the new Store module was generated properly and follows the established Company module structure.

## Validation Scenarios

### Scenario 1: Verify Directory Structure
**Prerequisites**: The implementation phase has completed.
**Action**:
List the generated files inside `Modules/Store/`:
```bash
find Modules/Store -type f
```
**Expected Outcome**:
You should see:
- `app/Models/Store.php`
- `app/Services/StoreService.php`
- `app/Http/Controllers/StoreController.php` (and optionally Api)
- `app/Http/Requests/StoreRequest.php`
- `app/DTOs/StoreDto.php`
- `resources/views/stores/index.blade.php` (and create/edit)
- `lang/en/general.php` and `lang/ar/general.php`

### Scenario 2: Verify Database Migration
**Prerequisites**: Migration file generated.
**Action**:
Run the migration:
```bash
php artisan migrate
```
**Expected Outcome**:
The `stores` table is created successfully with columns: `id`, `title` (json), `address` (json), `phone`, `is_active`, `lat`, `long`, timestamps.

### Scenario 3: Verify Translatable Behavior
**Prerequisites**: Application running or tested via tinker.
**Action**:
Use Laravel Tinker to create a store:
```bash
php artisan tinker
```
```php
use Modules\Store\Models\Store;

$store = Store::create([
    'title' => ['en' => 'English Title', 'ar' => 'العنوان العربي'],
    'address' => ['en' => 'English Address', 'ar' => 'العنوان بالعربي'],
    'phone' => '1234567890',
    'lat' => '37.123',
    'long' => '-122.123'
]);

echo $store->getTranslation('title', 'en');
echo $store->getTranslation('title', 'ar');
```
**Expected Outcome**:
The model persists correctly and `spatie/laravel-translatable` retrieves the localized values automatically.
