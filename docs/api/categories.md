# Category API Documentation

## Base URL
`domain/api/v1`

## Overview
This documentation provides detailed information about the Category API endpoints, including available methods, request parameters, and response formats.

### Authentication
- Most category endpoints are publicly accessible
- No authentication token is required for standard category operations
- Some advanced or administrative endpoints may require authentication

## Endpoints

### 1. List Categories
- **Endpoint:** `/categories`
- **Method:** `GET`
- **Description:** Retrieve a paginated and filterable list of categories
- **Authentication:** Not required

#### Query Parameters
| Parameter | Type | Required | Description | Default |
|-----------|------|----------|-------------|---------|
| `page` | Integer | No | Page number for pagination | 1 |
| `per_page` | Integer | No | Number of categories per page | 30 |
| `search` | String | No | Search term for category name or description | None |
| `parent_id` | Integer | No | Filter categories by parent category ID | None |
| `top_level` | Boolean | No | Show only top-level categories | `false` |
| `sort_by` | String | No | Field to sort results by | `created_at` |
| `sort_order` | String | No | Sort direction (`asc` or `desc`) | `desc` |
| `with[]` | Array | No | Include related model data | None |

#### Filtering Examples
1. Get top-level categories:
```
GET /api/v1/categories?top_level=true
```

2. Filter by parent category:
```
GET /api/v1/categories?parent_id=1
```

3. Search and include products:
```
GET /api/v1/categories?search=electronics&with[]=products
```

#### Success Response
```json
{
    "success": true,
    "results": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "Electronics",
                "description": "All electronic devices",
                "slug": "electronics",
                "parent_id": null,
                "image": "url/to/category/image.jpg"
            }
        ],
        "total": 15,
        "per_page": 15,
        "last_page": 2
    },
    "filter": {
        "top_level": true
    },
    "available_relationships": [
        "products",
        "subCategories",
        "parentCategory"
    ]
}
```

### 2. Get Single Category
- **Endpoint:** `/categories/{id}`
- **Method:** `GET`
- **Description:** Retrieve comprehensive details for a specific category
- **Authentication:** Not required

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | Integer | Yes | Unique category identifier |

#### Query Parameters
| Parameter | Type | Required | Description | Default |
|-----------|------|----------|-------------|---------|
| `with[]` | Array | No | Include related model data | None |

#### Request Examples
1. Basic category details:
```
GET /api/v1/categories/1
```

2. Include related models:
```
GET /api/v1/categories/1?with[]=products&with[]=subCategories
```

#### Success Response
```json
{
    "success": true,
    "category": {
        "id": 1,
        "name": "Electronics",
        "description": "All electronic devices",
        "description_full": "Comprehensive category description",
        "slug": "electronics",
        "meta_keywords": "electronics, gadgets, tech",
        "meta_description": "Wide range of electronic devices and gadgets",
        "image": "url/to/category/image.jpg",
        "parent_id": null,
        "products_count": 50,
        "sub_categories_count": 5
    },
    "available_relationships": [
        "products",
        "subCategories",
        "parentCategory"
    ],
    "included_relationships": ["products", "subCategories"]
}
```

#### Error Responses
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

## Related Model Inclusion

### Available Relationships
- `products`: List of products in this category
- `subCategories`: Child categories
- `parentCategory`: Parent category details

### Inclusion Methods
1. Array notation:
```
GET /api/v1/categories/1?with[]=products&with[]=subCategories
```

2. Comma-separated string:
```
GET /api/v1/categories/1?with=products,subCategories
```

## Best Practices
- Use pagination to manage large category lists
- Utilize filters to narrow down category searches
- Include related models when additional context is needed
- Handle potential errors gracefully in your client application

## Rate Limiting
- Maximum of 100 requests per minute
- Excessive requests may result in temporary IP blocking

## Error Handling
- `200`: Successful request
- `400`: Bad request (invalid parameters)
- `404`: Category not found
- `500`: Server error

## Versioning
This is version 1 (`/v1/`) of the Category API. Future versions may introduce breaking changes. 