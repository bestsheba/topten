# Product Search API

## Overview
The Product Search API allows you to search for products using a keyword across product names and descriptions.

## Endpoint
```
GET /api/v1/products/search
```

## Request Parameters

### Required Parameters
| Parameter | Type   | Description                     | Default | Constraints |
|-----------|--------|----------------------------------|---------|-------------|
| `keyword` | string | Search term to find products    | -       | Min 2 characters |

### Optional Parameters
| Parameter     | Type    | Description                      | Default | Constraints |
|---------------|---------|----------------------------------|---------|-------------|
| `per_page`    | integer | Number of results per page       | 30      | Min 1, Max 100 |
| `page`        | integer | Page number for pagination       | 1       | -           |
| `sort_by`     | string  | Field to sort results            | relevance | Allowed: 'relevance', 'price', 'created_at' |
| `sort_order`  | string  | Sort direction                   | desc    | 'asc' or 'desc' |
| `with`        | string  | Include related model relationships | -      | Comma-separated list |

### Allowed Relationships
You can include the following relationships using the `with` parameter:
- `brand`
- `category`
- `subCategory`
- `galleries`
- `sold`
- `variations`

## Example Requests

### Basic Search
```
GET /api/v1/products/search?keyword=phone
```

### Search with Pagination
```
GET /api/v1/products/search?keyword=phone&per_page=10&page=2
```

### Search with Sorting
```
GET /api/v1/products/search?keyword=phone&sort_by=price&sort_order=asc
```

### Search with Relationships
```
GET /api/v1/products/search?keyword=phone&with=brand,category
```

## Response Structure
```json
{
    "success": true,
    "results": {
        "data": [
            // Array of product objects
        ],
        "meta": {
            // Pagination metadata
        }
    },
    "keyword": "search term",
    "total_results": 42,
    "available_relationships": [
        "brand", "category", "subCategory", "galleries", "sold", "variations"
    ]
}
```

## Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "error": {
        "keyword": ["The keyword must be at least 2 characters."]
    },
    "error_code": "VALIDATION_ERROR"
}
```

### Server Error (500)
```json
{
    "success": false,
    "error": "Error message",
    "error_code": "SEARCH_ERROR"
}
```

## Notes
- Searches across product name and description
- Supports case-insensitive partial matching
- Pagination is built-in
- Flexible sorting and relationship inclusion 