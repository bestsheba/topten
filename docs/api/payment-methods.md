# Payment Methods API

## Get Payment Methods

### Endpoint
`GET /api/v1/payment-methods`

### Description
Retrieves available payment methods with their details from the system settings.

### Response

#### Success Response
- **Status Code:** `200 OK`
- **Content Type:** `application/json`

```json
{
    "success": true,
    "payment_methods": [
        {
            "name": "Cash on Delivery",
            "type": "offline",
            "description": "Pay when you receive your order"
        },
        {
            "name": "bKash",
            "type": "online",
            "description": "Mobile banking payment method",
            "number": "Optional bKash number",
            "note": "Optional bKash number note"
        },
        {
            "name": "Nagad",
            "type": "online",
            "description": "Mobile banking payment method",
            "number": "Optional Nagad number",
            "note": "Optional Nagad number note"
        },
        {
            "name": "Rocket",
            "type": "online",
            "description": "Mobile banking payment method",
            "number": "Optional Rocket number",
            "note": "Optional Rocket number note"
        },
        {
            "name": "Bank Account",
            "type": "online",
            "description": "Bank transfer payment method",
            "number": "Optional bank account number",
            "note": "Optional bank account number note"
        }
    ]
}
```

#### Response Fields
| Field | Type | Description |
|-------|------|-------------|
| `success` | Boolean | Indicates if the request was successful |
| `payment_methods` | Array | List of available payment methods and their details |
| `payment_methods[].name` | String | Name of the payment method |
| `payment_methods[].type` | String | Type of payment method (offline/online) |
| `payment_methods[].description` | String | Description of the payment method |
| `payment_methods[].number` | String/Null | Optional payment method specific number |
| `payment_methods[].note` | String/Null | Optional additional note for the payment method |

### Notes
- Payment methods and their details are configured in the admin settings
- Some payment method details may be null if not configured
- The list includes both offline and online payment options

### Example Request
```bash
curl https://yourdomain.com/api/v1/payment-methods
```

### Possible Error Responses
```json
{
    "success": false,
    "message": "Error description"
}
```
- Server errors will return appropriate HTTP error status codes
- Error response includes a message describing the encountered error

### Related Endpoints
- None 