# Delivery Charges API

## Get Delivery Charges

### Endpoint
`GET /api/v1/delivery-charges`

### Description
Retrieves the current delivery charges for different areas.

### Response

#### Success Response
- **Status Code:** `200 OK`
- **Content Type:** `application/json`

```json
{
    "delivery_fee_inside_dhaka": 50,
    "delivery_fee_outside_dhaka": 100
}
```

#### Response Fields
| Field | Type | Description |
|-------|------|-------------|
| `delivery_fee_inside_dhaka` | Number | Delivery charge for locations inside Dhaka |
| `delivery_fee_outside_dhaka` | Number | Delivery charge for locations outside Dhaka |

### Notes
- If no delivery fees are set, the API will return `0` for both fields
- Delivery fees can be configured in the admin settings

### Example Request
```bash
curl https://yourdomain.com/api/v1/delivery-charges
```

### Possible Error Responses
- If settings are not configured: Returns `0` for both delivery fees
- Server errors will return appropriate HTTP error status codes

### Related Endpoints
- None 