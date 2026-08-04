# 📺 TV App Frontend Integration Guide (Pairing Code & QR Code)

This document provides exact instructions and API specifications for Android TV / Smart TV Web App Frontend Developers to implement the **Pairing Code & QR Code Quick Login system**.

---

## 🔄 User Experience & Screen Flow (TV App)

1. **Launch Screen**:
   - When the TV app opens (unauthenticated state), display the **8-Digit Pairing Code** (e.g. `8F2A-9K3P`) along with its **QR Code**.
   - Show a countdown timer (e.g. 3 Minutes / 180 seconds).
   - Below the QR/Code, provide an option: *"Press OK to enter License Key manually"*.

2. **Background Polling**:
   - As soon as the Pairing Code is rendered, start polling the `POST /api/tv/pair-status` endpoint **every 3 seconds**.

3. **Auto-Login**:
   - Once the Hotel Admin approves the pairing from the Web Dashboard, the polling API will return state `paired` along with the **full login JSON response** (`auth.token`, `hotel`, `device`, `active_ott`, `menus`, etc.).
   - Save the `token` in local storage/shared preferences and transition to the **TV Main Dashboard**.

---

## 📡 API Integration Specs

### 1️⃣ Generate Pairing Code API
Call this endpoint on TV launch or when the previous code expires.

- **Endpoint**: `POST /api/tv/generate-pair-code`
- **Headers**: `Content-Type: application/json`, `Accept: application/json`
- **Request Body**:
```json
{
  "deviceId": "UNIQUE_HARDWARE_DEVICE_ID",
  "macAddress": "76:CD:9C:A4:54:BE",
  "ipAddress": "192.168.1.15",
  "model": "Sony Bravia 4K",
  "brand": "Sony",
  "osVersion": "11.0"
}
```

- **Response Body**:
```json
{
  "status": true,
  "message": "Pairing code generated successfully",
  "data": {
    "pair_code": "8F2A-9K3P",
    "expires_at": "2026-08-03T19:50:00+05:30",
    "expires_in_seconds": 180
  }
}
```

> 📌 **QR Code Generator Format**:
> Render the QR Code using the `pair_code` string directly or as a URL:
> `https://tvapp.digiemperor.com/hotel/devices?code=8F2A-9K3P`

---

### 2️⃣ Check Pairing Status (Polling API)
Poll this endpoint **every 3 seconds** while the TV screen displays the pairing code.

- **Endpoint**: `POST /api/tv/pair-status`
- **Headers**: `Content-Type: application/json`, `Accept: application/json`
- **Request Body**:
```json
{
  "pair_code": "8F2A-9K3P",
  "deviceId": "UNIQUE_HARDWARE_DEVICE_ID"
}
```

#### Possible Responses:

- **State 1: Waiting for pairing (Pending)**
  ```json
  {
    "status": true,
    "state": "pending",
    "message": "Waiting for hotel admin pairing..."
  }
  ```
  *(Action: Continue polling)*

- **State 2: Code Expired**
  ```json
  {
    "status": false,
    "state": "expired",
    "message": "Pairing code expired"
  }
  ```
  *(Action: Stop polling, automatically call `generate-pair-code` to fetch a fresh code)*

- **State 3: Paired & Successful Login (HTTP 200)**
  ```json
  {
    "status": true,
    "message": "TV Paired and logged in successfully!",
    "data": {
      "auth": {
        "token": "BEARER_TOKEN_HERE..."
      },
      "device": {
        "room_no": "104",
        "device_id": "UNIQUE_HARDWARE_DEVICE_ID",
        "mac_address": "76:CD:9C:A4:54:BE"
      },
      "hotel": {
        "hotel_name": "Grand Palace Hotel",
        "city": "Mumbai",
        "media": { ... },
        "active_plan": { ... }
      },
      "guest_info": { ... },
      "active_ott": [ ... ],
      "menus": [ ... ],
      "amenities": [ ... ]
    }
  }
  ```
  *(Action: Stop polling, save `token`, and enter main TV UI!)*

---

### 3️⃣ Backward Compatible Direct Login API
If the user chooses to type the License Key manually via TV remote.

- **Endpoint**: `POST /api/tv/login`
- **Request Body**:
```json
{
  "license_key": "XXXX-XXXX-XXXX-XXXX",
  "room_no": "104",
  "deviceId": "UNIQUE_HARDWARE_DEVICE_ID",
  "macAddress": "76:CD:9C:A4:54:BE",
  "ipAddress": "192.168.1.15",
  "model": "Sony Bravia 4K",
  "brand": "Sony",
  "osVersion": "11.0"
}
```
