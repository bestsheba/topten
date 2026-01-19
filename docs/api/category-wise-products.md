# Category Products API Documentation

## Base URL
`domain/api/v1`

## Overview
This documentation provides detailed information about retrieving products within a specific category, including filtering, sorting, and pagination options.

### Authentication
- Category products endpoint is publicly accessible
- No authentication token is required
- All users can view category-specific product information

## Endpoint

### Get Products by Category
- **Endpoint:** `/categories/{categoryId}/products`
- **Method:** `GET`
- **Description:** Retrieve paginated and filterable list of products within a specific category
- **Authentication:** Not required

### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `categoryId` | Integer | Yes | Unique identifier of the category |

### Query Parameters
| Parameter | Type | Required | Description | Default |
|-----------|------|----------|-------------|---------|
| `page` | Integer | No | Page number for pagination | 1 |
| `per_page` | Integer | No | Number of products per page | 30 |
| `brand_id` | Integer | No | Filter products by specific brand | None |
| `min_price` | Decimal | No | Minimum product price filter | None |
| `max_price` | Decimal | No | Maximum product price filter | None |
| `search` | String | No | Search term for product name or description | None |
| `sort_by` | String | No | Field to sort results by | `created_at` |
| `sort_order` | String | No | Sort direction (`asc` or `desc`) | `desc` |
| `with[]` | Array | No | Include related model data | None |

### Filtering Examples
1. Basic category products:
```
GET /api/v1/categories/1/products
```

2. Filter by brand and price:
```
GET /api/v1/categories/1/products?brand_id=2&min_price=10&max_price=100
```

3. Search and sort products:
```
GET /api/v1/categories/1/products?search=smartphone&sort_by=price&sort_order=asc
```

4. Include related models:
```
GET /api/v1/categories/1/products?with[]=brand&with[]=variations
```

### Success Response
```json
{
    "success": true,
    "category": {
        "id": 1,
        "name": "Electronics",
        "slug": "electronics"
    },
    "results": {
        "current_page": 1,
        "data": [
            {
                "id": 101,
                "name": "Smartphone",
                "description": "Latest model smartphone",
                "price": 50.00,
                "category_id": 1,
                "brand_id": 2,
                "slug": "latest-smartphone"
            }
        ],
        "total": 15,
        "per_page": 30,
        "last_page": 1
    },
    "filter": {
        "brand_id": 2,
        "min_price": 10,
        "max_price": 100
    },
    "available_relationships": [
        "brand",
        "category", 
        "subCategory", 
        "galleries", 
        "sold", 
        "variations"
    ]
}
```

### Related Model Inclusion
#### Available Relationships
- `brand`: Product's brand details
- `category`: Product's category information
- `subCategory`: Product's subcategory details
- `galleries`: Product image galleries
- `sold`: Sales information
- `variations`: Product variations

#### Inclusion Methods
1. Array notation:
```
GET /api/v1/categories/1/products?with[]=brand&with[]=variations
```

2. Comma-separated string:
```
GET /api/v1/categories/1/products?with=brand,variations
```

### Error Responses
1. Category Not Found
- **Status Code:** `404 Not Found`
```json
{
    "success": false,
    "error": "Category not found",
    "error_code": "CATEGORY_NOT_FOUND"
}
```

2. Server Error
- **Status Code:** `500 Internal Server Error`
```json
{
    "success": false,
    "error": "Detailed error message",
    "error_code": "INTERNAL_SERVER_ERROR"
}
```

### Sorting Options
You can sort by any product model attribute:
- `created_at` (default)
- `price`
- `name`
- `stock`

### Best Practices
- Always use pagination to manage large product lists
- Utilize filters to narrow down product searches
- Include related models when additional context is needed
- Handle potential errors gracefully in your client application

### Rate Limiting
- Maximum of 100 requests per minute
- Excessive requests may result in temporary IP blocking

### Versioning
This is version 1 (`/v1/`) of the Category Products API. Future versions may introduce breaking changes. 