# Flash Deal API Documentation

## Base URL
`domain/api/v1`

## Overview
This API endpoint provides information about current flash deals, including deal status, timer, and featured products.

### Authentication
- Flash deal endpoint is publicly accessible
- No authentication token is required
- All users can view current flash deal information

## Endpoint

### Get Flash Deal Information
- **Endpoint:** `/flash-deals`
- **Method:** `GET`
- **Description:** Retrieve current flash deal status, timer, and featured products
- **Authentication:** Not required

### Response Parameters

#### Flash Deal Object
| Parameter | Type | Description |
|-----------|------|-------------|
| `is_active` | Boolean | Whether the flash deal is currently active |
| `timer` | Timestamp | End time of the flash deal |
| `remaining_time` | Integer | Seconds remaining until flash deal ends |

#### Products Array (when active)
| Parameter | Type | Description |
|-----------|------|-------------|
| `id` | Integer | Product unique identifier |
| `name` | String | Product name |
| `slug` | String | Product URL slug |
| `price` | Decimal | Original product price |
| `offer_price` | Decimal | Discounted price during flash deal |
| `image` | String | Product image URL |
| `sold_count` | Integer | Number of products sold |

### Success Response
```json
{
    "success": true,
    "flash_deal": {
        "is_active": true,
        "timer": "2024-02-15T23:59:59Z",
        "remaining_time": 86400
    },
    "products": [
        {
            "id": 101,
            "name": "Smartphone",
            "slug": "latest-smartphone",
            "price": 50.00,
            "offer_price": 39.99,
            "image": "url/to/product/image.jpg",
            "sold_count": 25
        }
    ]
}
```

### Inactive Flash Deal Response
```json
{
    "success": true,
    "flash_deal": {
        "is_active": false,
        "timer": null,
        "remaining_time": null
    }
}
```

### Error Response
```json
{
    "success": false,
    "error": "Detailed error message",
    "error_code": "FLASH_DEAL_ERROR"
}
```

### Best Practices
- Check `is_active` before displaying flash deal
- Use `remaining_time` for countdown timer
- Refresh data periodically to get latest flash deal status

### Rate Limiting
- Maximum of 100 requests per minute
- Excessive requests may result in temporary IP blocking

### Versioning
This is version 1 (`/v1/`) of the Flash Deal API. Future versions may introduce breaking changes. 