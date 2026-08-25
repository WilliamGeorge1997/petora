# Interface Contracts: Company Module

## HTTP API Contracts

All JSON API responses follow the Petora Response Envelope pattern defined in the Constitution.

### 1. Get Companies List
**Endpoint**: `GET /api/company/companies`
**Auth**: Sanctum (`auth:sanctum` middleware)
**Response**:
```json
{
  "status": true,
  "message": "Companies retrieved successfully",
  "data": [
    {
      "id": 1,
      "title": "Example Company",
      "phone": "+1234567890",
      "address": "123 Main St",
      "is_active": true
    }
  ]
}
```

## Method Contracts

### `CompanyService`
```php
function findAll(array $data = [], array $relations = []): mixed;
function findById(int $id): \Modules\Company\Models\Company;
function findBy(string $key, mixed $value): \Illuminate\Database\Eloquent\Collection;
function save(array $data): \Modules\Company\Models\Company;
function update(int $id, array $data): \Modules\Company\Models\Company;
function activate(int $id): void;
function delete(int $id): void;
```
