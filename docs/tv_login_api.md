# HotelTV Connect - TV Login API Documentation

This documentation provides the details required for integrating the TV/Mobile client application with the HotelTV Connect backend server.

---

## 1. Endpoint Details

*   **HTTP Method:** `POST`
*   **URL:** `{{BASE_URL}}/api/tv/login`
*   **Headers:**
    *   `Content-Type: application/json`
    *   `Accept: application/json`

---

## 2. Request Payload

### JSON Schema
```json
{
  "license_key": "string (Required)",
  "room_no": "string (Required)",
  "deviceId": "string (Required)",
  "macAddress": "string (Required)",
  "ipAddress": "string (Optional)",
  "model": "string (Optional)",
  "brand": "string (Optional)",
  "osVersion": "string (Optional)"
}
```

### Developer Instructions
> [!IMPORTANT]
> **Input Requirements for TV App Developers:**
> - **User Manual Inputs:** The `license_key` (16-digit alphanumeric key) and `room_no` must be inputted manually by the hotel staff or user in the TV login interface.
> - **Silent Hardware Inputs:** The parameters `deviceId` (e.g. Android Secure ID), `macAddress`, `ipAddress`, `model`, `brand`, and `osVersion` **MUST** be fetched automatically from the TV/device hardware system APIs and passed silently in the request payload. Do not expose these hardware fields to the user.

---

## 3. Simulated API Responses

### **A. Success Response (200 OK)**
Returned when the license key is valid, the registration limits are respected, and the TV is successfully authorized.

```json
{
  "success": true,
  "message": "TV logged in successfully.",
  "device_id": "6231A4D7B13402C5",
  "mac_address": "AA:BB:CC:DD:EE:FF",
  "hotel": {
    "id": 1,
    "owner_name": "John Doe",
    "email": "owner@grandhotel.com",
    "phone": "+919876543210",
    "hotel_name": "The Grand Hotel",
    "hotel_location": "Mumbai, India",
    "hotel_logo": "uploads/hotel_logos/1720092305_logo_a8d7f2a1.png",
    "hotel_image": "uploads/hotel_images/1720092305_image_c8f2b1d3.jpg",
    "room_count": 50,
    "plan_id": 2,
    "payment_status": "paid",
    "razorpay_order_id": "order_Ok39sJd912sk",
    "razorpay_payment_id": "pay_mock_98a7sd89a7sd9a",
    "license_key": "ABCD-EFGH-IJKL-MNOP",
    "approval_status": "approved",
    "status": true,
    "created_at": "2026-07-02T10:00:00.000000Z",
    "updated_at": "2026-07-04T12:00:00.000000Z",
    "plan": {
      "id": 2,
      "name": "Premium Plan",
      "room_count": 50,
      "price": "4999.00",
      "status": true,
      "description": "Standard business plan supporting up to 50 smart TV integrations.",
      "created_at": "2026-07-02T09:00:00.000000Z",
      "updated_at": "2026-07-02T09:00:00.000000Z"
    }
  }
}
```

### **B. Error - Invalid or Inactive License Key (403 Forbidden)**
Returned when the `license_key` does not exist, is disabled by the admin, or is pending approval.

```json
{
  "success": false,
  "message": "Invalid or inactive license key"
}
```

### **C. Error - Device Connection Limit Reached (403 Forbidden)**
Returned when the license key is valid but the number of currently active TVs reaches the hotel's maximum room/TV limit (`room_count`).

```json
{
  "success": false,
  "message": "Device limit reached for this license"
}
```

### **D. Error - Validation Fail (422 Unprocessable Content)**
Returned when required inputs (such as `license_key` or `deviceId`) are missing.

```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "deviceId": [
      "The device id field is required."
    ],
    "macAddress": [
      "The mac address field is required."
    ]
  }
}
```
