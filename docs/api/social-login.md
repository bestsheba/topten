# Social Login and OTP Verification API

## Overview
This document describes the Social Login and OTP (One-Time Password) verification API endpoints for secure authentication across multiple social providers.

## Supported Social Providers
- Google
- Facebook
- GitHub
- Twitter
- LinkedIn

## Endpoints

### 1. Social Login
```http
POST /api/v1/social-login
```

#### Request Body
```json
{
    "provider": "google", // Required, supported: google, facebook, github, twitter, linkedin-openid
    "access_token": "social_oauth_token", // Required, OAuth token from provider
    "name": "John Doe", // Required, user's full name
    "email": "john@example.com", // Required, user's email address
    "provider_id": "unique_provider_identifier", // Required, user's ID from provider
    "avatar": "https://example.com/avatar.jpg" // Optional, user's profile picture
}
```

#### Responses
- **Success (200 OK)**
```json
{
    "status": 1,
    "message": "Social login successful",
    "token": "sanctum_access_token",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "google_id": "provider_unique_id",
        "social_provider": "google",
        "avatar": "https://example.com/avatar.jpg"
    }
}
```

- **Error Responses**
1. Validation Error (400 Bad Request)
```json
{
    "message": {
        "provider": ["The provider field is required."],
        "access_token": ["The access token field is required."]
    }
}
```

2. Token Verification Failure (401 Unauthorized)
```json
{
    "status": 0,
    "message": "Invalid access token"
}
```

3. Server Error (500 Internal Server Error)
```json
{
    "status": 0,
    "message": "Social login failed",
    "error": "Detailed error message"
}
```

### 2. Token Verification
```http
POST /api/v1/verify-social-token
```

#### Request Body
```json
{
    "provider": "google", // Required, supported: google, facebook
    "access_token": "social_oauth_token" // Required
}
```

#### Responses
- **Success (200 OK)**
```json
{
    "status": 1,
    "message": "Token is valid",
    "data": null
}
```

- **Invalid Token**
```json
{
    "status": 0,
    "message": "Token is invalid"
}
```

### 3. OTP Verification
```http
POST /api/v1/otp/verify
```

#### Authentication
- Requires Bearer Token (Sanctum Authentication)

#### Request Body
```json
{
    "otp": "123456" // Required, 6-digit OTP
}
```

#### Responses
- **Success (200 OK)**
```json
{
    "message": "OTP verified successfully",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "otp_verified_at": "2024-01-15T10:30:45.000000Z"
    }
}
```

- **Error (422 Unprocessable Entity)**
```json
{
    "message": "Invalid or expired OTP",
    "errors": {
        "otp": ["Invalid or expired OTP"]
    }
}
```

### 4. Resend OTP
```http
POST /api/v1/otp/resend
```

#### Authentication
- Requires Bearer Token (Sanctum Authentication)

#### Responses
- **Success (200 OK)**
```json
{
    "message": "New OTP sent successfully"
}
```

- **Error (422 Unprocessable Entity)**
```json
{
    "message": "User not found",
    "errors": {
        "user": ["User not found"]
    }
}
```

## Social Login Process
1. Client obtains OAuth token from social provider
2. Send token with user details to `/api/v1/social-login`
3. Server verifies token with the social provider
4. User is created or retrieved based on email
5. OTP is generated and sent via email
6. Sanctum token is returned for authentication

## Security Measures
- Token verification with social provider
- Email and provider ID validation
- Random password generation for social login users
- OTP-based additional verification
- OTPs are single-use and time-limited

## Token Verification
The system supports manual token verification via `/api/v1/verify-social-token` endpoint, which checks token validity with the respective social provider.

## Error Handling
- Comprehensive error responses
- Validation of all input parameters
- Secure token verification process
- Graceful handling of token and login failures

## Rate Limiting
- Endpoints are subject to rate limiting to prevent abuse
- Specific rate limit configurations should be defined in the application

## Prerequisites
- Configured OAuth credentials for supported social providers
- Laravel Socialite package
- Sanctum authentication
- Configured mail services for OTP delivery

## Notes
- Supports multiple social login providers
- Provides an additional layer of security with OTP verification
- Seamless user creation and authentication process
