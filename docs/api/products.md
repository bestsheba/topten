# Product API Documentation

## Base URL
`domain/api/v1`

## Overview
This documentation provides detailed information about the Product API endpoints, including available methods, request parameters, and response formats.

### Authentication
- Product endpoints are publicly accessible
- No authentication token is required for standard product operations
- Some advanced features (like adding to wishlist) may require authentication

## Endpoints

### 1. List Products
- **Endpoint:** `/products`
- **Method:** `GET`
- **Description:** Retrieve a paginated and filterable list of products
- **Authentication:** Not required

#### Query Parameters
| Parameter | Type | Required | Description | Default |
|-----------|------|----------|-------------|---------|
| `page` | Integer | No | Page number for pagination | 1 |
| `per_page` | Integer | No | Number of products per page | 30 |
| `category_id` | Integer | No | Filter products by specific category | None |
| `brand_id` | Integer | No | Filter products by specific brand | None |
| `min_price` | Decimal | No | Minimum product price filter | None |
| `max_price` | Decimal | No | Maximum product price filter | None |
| `search` | String | No | Search term for product name or description | None |
| `sort_by` | String | No | Field to sort results by | `created_at` |
| `sort_order` | String | No | Sort direction (`asc` or `desc`) | `desc` |
| `with[]` | Array | No | Include related model data | None |

#### Filtering Examples
1. Filter by category and price range:
```
GET /api/v1/products?category_id=1&min_price=10&max_price=100
```

2. Search and sort products:
```
GET /api/v1/products?search=smartphone&sort_by=price&sort_order=asc
```

3. Pagination with custom page size:
```
GET /api/v1/products?page=2&per_page=15
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
                "name": "Smartphone",
                "description": "Latest model smartphone",
                "price": 50.00,
                "category_id": 1,
                "brand_id": 2,
                "slug": "latest-smartphone",
                "stock": 100
            }
        ],
        "total": 15,
        "per_page": 15,
        "last_page": 2
    },
    "filter": {
        "category_id": 1,
        "min_price": 10,
        "max_price": 100
    }
}
```

### 2. Get Single Product
- **Endpoint:** `/products/{id}`
- **Method:** `GET`
- **Description:** Retrieve comprehensive details for a specific product
- **Authentication:** Not required

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | Integer | Yes | Unique product identifier |

#### Query Parameters
| Parameter | Type | Required | Description | Default |
|-----------|------|----------|-------------|---------|
| `with[]` | Array | No | Include related model data | None |

#### Request Examples
1. Basic product details:
```
GET /api/v1/products/1
```

2. Include related models:
```
GET /api/v1/products/1?with[]=brand&with[]=category
```

#### Success Response
```json
{
    "success": true,
    "product": {
        "id": 1,
        "name": "Smartphone",
        "description": "Latest model smartphone",
        "description_full": "Comprehensive product description with detailed specifications",
        "price": 50.00,
        "category_id": 1,
        "brand_id": 2,
        "slug": "latest-smartphone",
        "stock": 100,
        "additional_details": {
            "warranty": "1 year",
            "packaging": "Eco-friendly box"
        },
        "meta_keywords": "smartphone, tech, latest model",
        "meta_description": "Advanced smartphone with cutting-edge features",
        "images": [
            "url/to/image1.jpg",
            "url/to/image2.jpg"
        ],
        "variations": [
            {
                "id": 101,
                "color": "Black",
                "storage": "128GB",
                "price": 50.00
            }
        ]
    },
    "available_relationships": [
        "brand", 
        "category", 
        "subCategory", 
        "galleries", 
        "sold", 
        "variations"
    ],
    "included_relationships": ["brand", "category"]
}
```

#### Error Responses
1. Product Not Found
- **Status Code:** `404 Not Found`
```json
{
    "success": false,
    "error": "Product not found",
    "error_code": "PRODUCT_NOT_FOUND"
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

#### Related Model Inclusion
- Use the same methods as in the product list endpoint
- Supports array and comma-separated string notations
- Available relationships:
  - `brand`
  - `category`
  - `subCategory`
  - `galleries`
  - `sold`
  - `variations`

#### Additional Notes
- Includes additional product details not visible in list view
- Supports dynamic inclusion of related models
- Provides clear error codes for different failure scenarios

## Advanced Usage

### Related Model Inclusion
You can include related models in three ways:

1. Using array notation:
```
GET /api/v1/products?with[]=category&with[]=brand
```

2. Using comma-separated string:
```
GET /api/v1/products?with=category,brand
```

3. Available Relationships:
- `brand`
- `category`
- `subCategory`
- `galleries`
- `sold`
- `variations`

#### Example Response with Related Models
```json
{
    "success": true,
    "results": {
        "data": [
            {
                "id": 1,
                "name": "Smartphone",
                "brand": {
                    "id": 2,
                    "name": "Tech Brand"
                },
                "category": {
                    "id": 1,
                    "name": "Electronics"
                }
            }
        ]
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

**Notes:**
- Only allowed relationships will be included
- Multiple relationships can be requested
- Requesting non-existent relationships will be silently ignored

### Sorting Options
You can sort by any product model attribute:
- `created_at` (default)
- `price`
- `name`
- `stock`

### Search Capabilities
The search parameter looks for matches in:
- Product name
- Product description

## Best Practices
- Always use pagination to manage large product lists
- Utilize filters to narrow down product searches
- Include related models when additional context is needed
- Handle potential errors gracefully in your client application

## Rate Limiting
- Maximum of 100 requests per minute
- Excessive requests may result in temporary IP blocking

## Error Handling
- `200`: Successful request
- `400`: Bad request (invalid parameters)
- `404`: Product not found
- `500`: Server error

## Versioning
This is version 1 (`/v1/`) of the Product API. Future versions may introduce breaking changes. 