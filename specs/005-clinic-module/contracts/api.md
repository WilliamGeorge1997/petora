# API Contracts: Clinic Module

## GET `/api/clinic`

Fetches a list of active clinics for the Flutter app.

**Method**: GET
**Authentication**: Sanctum Bearer Token (`client` or `driver` guards as applicable).
**Headers**:
- `Accept`: `application/json`
- `Accept-Language`: `en` or `ar`

**Query Parameters**:
- `paginated`: `int` (Default: 20)

**Response (Success)**:
```json
{
  "status": true,
  "message": "Clinics fetched successfully.",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Happy Pets Clinic",
        "description": "Full service veterinary clinic.",
        "image": "http://localhost/uploads/clinic/1234_logo.jpg",
        "address": "123 Pet Street, City",
        "phone": "+1234567890",
        "lat": "24.7136",
        "long": "46.6753",
        "is_active": true
      }
    ],
    "first_page_url": "...",
    "from": 1,
    "last_page": 1,
    "last_page_url": "...",
    "next_page_url": null,
    "path": "...",
    "per_page": 20,
    "prev_page_url": null,
    "to": 1,
    "total": 1
  }
}
```
