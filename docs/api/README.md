# API Documentation

## Available Endpoints

### Public Endpoints
- [Delivery Charges](/delivery-charges.md)
  - `GET /api/v1/delivery-charges`: Retrieve current delivery fees
- [Payment Methods](/payment-methods.md)
  - `GET /api/v1/payment-methods`: Retrieve available payment methods

### Authentication Required Endpoints
- Profile Management
  - `GET /api/v1/profile`: Fetch user profile
  - `POST /api/v1/profile`: Update user profile
- Order Management
  - `GET /api/v1/orders`: List customer orders
  - `GET /api/v1/orders/{id}`: Get single order details

### Authentication
- `POST /api/v1/login`: User login
- `POST /api/v1/register`: User registration
- `POST /api/v1/logout`: User logout (requires authentication)

## General Information
- Base URL: `https://yourdomain.com/api/v1`
- Authentication: Uses Laravel Sanctum
- Content Type: `application/json`

## Error Handling
- Standard HTTP status codes
- Error responses include error message and code

## Rate Limiting
- Default Laravel rate limiting applies

## Version
- Current API Version: v1 