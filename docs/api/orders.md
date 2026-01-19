# Orders API

## Place Order

### Endpoint
`POST /api/v1/place-order`

### Authentication
- **Required**: Yes
- **Method**: Bearer Token (Sanctum/Passport)
- An active authentication token must be included in the request header

### Request Parameters

#### Request Body (JSON/Form-data)

| Parameter | Type | Required | Description | Constraints | Example |
|-----------|------|----------|-------------|-------------|---------|
| `name` | String | Yes | Customer's full name | Max 255 characters | `"Jone Do"` |
| `phone_number` | String | Yes | Customer's contact number | Max 255 characters | `"018656656676777"` |
| `address` | String | Yes | Full delivery address | - | `"Noakhali, Bangladesh"` |
| `delivery_area` | String | Yes | Delivery location | Must be `inside_dhaka` or `outside_dhaka` | `"inside_dhaka"` |
| `payment_method` | String | Yes | Payment method | Must be `online` or `cash_on_delivery` | `"cash_on_delivery"` |
| `payment_method_gateway` | String | Conditional | Payment gateway (required if `payment_method` is `online`) | - | `"bKash"` |
| `transaction_id` | String | Conditional | Transaction ID (required if `payment_method` is `online`) | - | `null` |
| `cart` | Array | Yes | Array of cart items | Must contain at least one item | `[{"product_id": 18, "variation_id": null, "quantity": 1}]` |

#### Cart Item Structure
Each cart item must contain:
- `product_id`: ID of the product
- `variation_id` (optional): ID of the product variation
- `quantity`: Number of items

### Response

#### Success Response (200 OK)
```json
{
  "success": true,
  "message": "Order placed successfully!",
  "order": {
    "id": 1,
    "user_id": 1,
    "total": 500,
    "delivery_charge": 50,
    "subtotal": 450,
    "status": 1,
    "payment_method": "cash_on_delivery",
    "customer_name": "Jone Do",
    "customer_phone_number": "018656656676777",
    "customer_address": "Noakhali, Bangladesh"
  }
}
```

#### Error Responses

1. Empty Cart (400 Bad Request)
```json
{
  "success": false,
  "error": "Your cart is empty.",
  "error_code": "CART_EMPTY"
}
```

2. Validation Error (400 Bad Request)
```json
{
  "success": false,
  "error": "Failed to place order. Please try again."
}
```

### Additional Notes
- Delivery charges are automatically calculated based on the delivery area
- Coupon discounts are applied if a valid coupon is in the session
- Order items are created along with the main order
- A notification is sent to the user upon successful order placement

### Example Request
```bash
curl -X POST https://yourdomain.com/api/v1/place-order \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Jone Do",
    "phone_number": "018656656676777",
    "address": "Noakhali, Bangladesh",
    "delivery_area": "inside_dhaka",
    "payment_method": "cash_on_delivery",
    "cart": [
      {
        "product_id": 18,
        "variation_id": null,
        "quantity": 1
      },
      {
        "product_id": 18,
        "variation_id": 4,
        "quantity": 1
      }
    ]
  }'
``` 