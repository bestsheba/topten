# Sliders API Documentation

## Base URL
`domain/api/v1`

## Overview
This API endpoint provides access to active sliders, supporting both regular and offer sliders.

### Authentication
- Slider endpoint is publicly accessible
- No authentication token is required
- All users can view slider information

## Endpoint

### Get Sliders
- **Endpoint:** `/sliders`
- **Method:** `GET`
- **Description:** Retrieve active sliders
- **Authentication:** Not required

### Query Parameters
| Parameter | Type | Required | Description | Default |
|-----------|------|----------|-------------|---------|
| `type` | String | No | Filter slider type | `all` |

#### Allowed `type` Values
- `all`: Return both regular and offer sliders
- `regular`: Return only regular sliders
- `offer`: Return only offer sliders

### Slider Object Properties
#### Regular Slider
| Parameter | Type | Description |
|-----------|------|-------------|
| `id` | Integer | Unique slider identifier |
| `title` | String | Slider title |
| `description` | String | Slider description |
| `image` | String | Full image URL |
| `link` | String | Optional link associated with slider |
| `type` | String | Always `regular` |

#### Offer Slider
| Parameter | Type | Description |
|-----------|------|-------------|
| `id` | Integer | Unique slider identifier |
| `title` | String | Slider title |
| `image` | String | Full image URL |
| `link` | String | Optional link associated with slider |
| `type` | String | Always `offer` |

### Request Examples
1. Get all sliders:
```
GET /api/v1/sliders
```

2. Get only regular sliders:
```
GET /api/v1/sliders?type=regular
```

3. Get only offer sliders:
```
GET /api/v1/sliders?type=offer
```

### Success Response
```json
{
    "success": true,
    "sliders": [
        {
            "id": 1,
            "title": "Summer Sale",
            "description": "Amazing discounts this summer",
            "image": "https://example.com/slider1.jpg",
            "link": "https://example.com/summer-sale",
            "type": "regular"
        },
        {
            "id": 2,
            "title": "Special Offer",
            "image": "https://example.com/offer-slider.jpg",
            "link": "https://example.com/special-offer",
            "type": "offer"
        }
    ]
}
```

**Note:** All sliders are retrieved, regardless of any status. The `type` parameter allows filtering between regular and offer sliders.

### Error Response
```json
{
    "success": false,
    "error": "Detailed error message",
    "error_code": "SLIDER_FETCH_ERROR"
}
```

### Best Practices
- Check `type` to differentiate between slider types
- Use `link` for redirecting users to specific pages
- Always verify image URLs before displaying
- Sliders are retrieved and sorted by their ID

### Rate Limiting
- Maximum of 100 requests per minute
- Excessive requests may result in temporary IP blocking

### Versioning
This is version 1 (`/v1/`) of the Sliders API. Future versions may introduce breaking changes. 