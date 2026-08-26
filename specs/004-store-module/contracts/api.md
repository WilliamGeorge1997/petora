# Store Module API Contracts

Following the exact structure of the `Company` module API and Admin routes.

## Admin Dashboard Routes (Blade)
- **Prefix**: `/admin/stores`
- **Controller**: `StoreController`
- **Actions**: Resource routes generated via `Route::resource('stores', StoreController::class)->names('admin.stores');`

## API Routes (Flutter)
- **Prefix**: `/api/stores`
- **Controller**: `StoreController` (Api namespace)
- **Actions**: `GET /api/stores` (List), `GET /api/stores/{id}` (Show), etc., following the standard API envelope:

### Response Format
All API responses must use the `success()` and `failure()` helpers from `ResponseHelper`.

```json
{
  "status": true,
  "message": "Stores retrieved successfully",
  "data": [
    {
      "id": 1,
      "title": "Petco Central",
      "address": "123 Main St",
      "phone": "555-1234",
      "is_active": true,
      "lat": "37.7749",
      "long": "-122.4194"
    }
  ]
}
```
