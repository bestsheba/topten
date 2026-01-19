# Authentication API Documentation

## Base URL
`domain/api/v1`

## Authentication Endpoints

### 1. User Registration
- **Endpoint:** `/register`
- **Method:** `POST`
- **Description:** Register a new user

#### Request Parameters
| Parameter | Type | Required | Validation Rules |
|-----------|------|----------|-----------------|
| `name` | String | Yes | Max 255 characters |
| `email` | String | Yes | Valid email, unique, max 255 characters |
| `password` | String | Yes | Minimum 8 characters, confirmed |
| `password_confirmation` | String | Yes | Must match password |

#### Request Example
```json
{
    "name": "John Doe",
    "email": "john@example.com", 
    "password": "StrongPassword123!",
    "password_confirmation": "StrongPassword123!"
}
```

#### Success Response
- **Status Code:** `201 Created`
```json
{
    "message": "Registration successful",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "token": "api_token_string"
}
```

#### Error Responses
- **Status Code:** `422 Unprocessable Entity` (Validation Error)
```json
{
    "message": "Validation failed",
    "errors": {
        "email": ["The email has already been taken."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

### 2. User Login
- **Endpoint:** `/login`
- **Method:** `POST`
- **Description:** Authenticate user and generate API token

#### Request Parameters
| Parameter | Type | Required | Validation Rules |
|-----------|------|----------|-----------------|
| `email` | String | Yes | Valid email |
| `password` | String | Yes | Correct credentials |

#### Request Example
```json
{
    "email": "john@example.com",
    "password": "StrongPassword123!"
}
```

#### Success Response
- **Status Code:** `200 OK`
```json
{
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "token": "api_token_string"
}
```

#### Error Responses
- **Status Code:** `401 Unauthorized`
```json
{
    "message": "Authentication failed",
    "error": "Invalid credentials"
}
```

### 3. User Logout
- **Endpoint:** `/logout`
- **Method:** `POST`
- **Description:** Revoke user's API tokens
- **Authentication:** Required (Bearer Token)

#### Request Headers
| Header | Value | Description |
|--------|-------|-------------|
| `Authorization` | `Bearer {api_token}` | API token received during login |

#### Success Response
- **Status Code:** `200 OK`
```json
{
    "message": "Logged out successfully"
}
```

## Forget Password
- **Endpoint:** `/forget-password`
- **Method:** `POST`
- **Description:** Request a password reset link
- **Authentication:** Not required

### Request Body
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `email` | String | Yes | Registered user email address |

### Request Example
```json
{
    "email": "user@example.com"
}
```

### Success Response
```json
{
    "success": true,
    "message": "Password reset link sent to your email"
}
```

### Error Responses
1. Validation Error
```json
{
    "message": "The email field is required.",
    "errors": {
        "email": ["Email is required"]
    }
}
```

2. Server Error
```json
{
    "success": false,
    "error": "Unable to process password reset request",
    "error_code": "FORGET_PASSWORD_ERROR"
}
```

### Notes
- The reset link is valid for 60 minutes
- Check spam folder if email is not received
- Only registered emails will receive a reset link

## Authentication Flow
1. Register a new user
2. Receive API token
3. Use token in `Authorization` header for authenticated requests
4. Logout to invalidate tokens

## Error Handling
- Validation errors return `422` status
- Authentication errors return `401` status
- Server errors return `500` status

## Security Notes
- Passwords are hashed before storage
- API tokens are generated using Laravel Sanctum
- Tokens can be revoked on logout 

## Profile Management

### Get Profile Details
- **Endpoint:** `/profile`
- **Method:** `GET`
- **Authentication:** Required (Sanctum Token)
- **Description:** Retrieve comprehensive user profile information

#### Success Response
```json
{
    "success": true,
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "avatar": "https://example.com/avatar.jpg",
        "phone_number": "1234567890",
        "address": "123 Main St, City, Country"
    }
}
```

### Update Profile
- **Endpoint:** `/profile`
- **Method:** `POST`
- **Authentication:** Required (Sanctum Token)
- **Content-Type:** `multipart/form-data`
- **Description:** Update user profile details and avatar

#### Request Parameters
| Parameter | Type | Required | Description | Validation |
|-----------|------|----------|-------------|------------|
| `name` | String | No | User's full name | Max 255 characters |
| `phone_number` | String | No | User's phone number | Max 20 characters |
| `address` | String | No | User's address | Max 500 characters |
| `avatar` | File | No | Profile picture | Image file, max 2MB |

#### Request Examples
1. Update name and phone number
```bash
curl -X POST /api/v1/profile \
  -H "Authorization: Bearer {token}" \
  -F "name=John Updated" \
  -F "phone_number=9876543210"
```

2. Update avatar
```bash
curl -X POST /api/v1/profile \
  -H "Authorization: Bearer {token}" \
  -F "avatar=@/path/to/new/avatar.jpg"
```

3. Full profile update
```bash
curl -X POST /api/v1/profile \
  -H "Authorization: Bearer {token}" \
  -F "name=John Doe" \
  -F "phone_number=1234567890" \
  -F "address=123 Main St" \
  -F "avatar=@/path/to/avatar.jpg"
```

#### Success Response
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "avatar": "https://example.com/new-avatar.jpg",
        "phone_number": "1234567890",
        "address": "123 Main St"
    }
}
```

#### Error Responses
1. Validation Error
```json
{
    "message": "The name field is required.",
    "errors": {
        "name": ["Name is required"]
    }
}
```

2. Server Error
```json
{
    "success": false,
    "error": "Unable to update profile",
    "error_details": "Detailed error message",
    "error_code": "PROFILE_UPDATE_ERROR"
}
```

### Profile Management Notes
- All profile update fields are optional
- Avatar must be a valid image file (max 2MB)
- Phone number and address are stored in a separate address table
- Profile picture is stored in the `storage/avatars` directory
- Existing values are preserved if not explicitly updated

### Profile Picture Handling
- If no avatar is provided, existing avatar remains unchanged
- Uploading a new avatar automatically deletes the previous one
- Default avatar is generated if no custom avatar is set

### Best Practices
- Always include authentication token
- Validate input on client-side before submission
- Handle potential network errors gracefully
- Refresh user interface after successful profile update 