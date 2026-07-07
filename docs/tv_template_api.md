# HotelTV Connect - TV Template Update API Documentation

This documentation provides the details required for the TV/Mobile client application to check and download offline web templates from the backend server.

---

## 1. Endpoint Details

*   **HTTP Method:** `GET`
*   **URL:** `{{BASE_URL}}/api/tv/template/check-version`
*   **Headers:**
    *   `Accept: application/json`
    *   `Authorization: Bearer {{TV_AUTH_TOKEN}}` (Use the `token` received during the TV login success response)

---

## 2. Request Details

No request body payload is required for this endpoint. Authentication must be passed securely in the headers.

---

## 3. Simulated API Responses

### **A. Success Response (200 OK)**
Returned when the bearer token is valid and the latest template and hotel details are successfully fetched.

```json
{
  "status": true,
  "message": "Template version details fetched successfully.",
  "data": {
    "auth": {
      "token": "d7a8f9b0c1d2e3f4g5h6i7j8k9l0m1n2..."
    },
    "template": {
      "latest_version": "2.0",
      "old_version": "1.5",
      "download_url": "https://tvapp.digiemperor.com/uploads/templates/template_v2_0_1720098450.zip",
      "uploaded_at": "2026-07-04T15:09:33+00:00",
      "is_update_available": true
    },
    "device": {
      "room_no": "101",
      "device_id": "6231A4D7B13402C5",
      "mac_address": "AA:BB:CC:DD:EE:FF",
      "ip_address": "192.168.1.1",
      "model": "SmartTV Pro",
      "brand": "Samsung",
      "os_version": "Tizen 6.0"
    },
    "hotel": {
      "hotel_name": "The Grand Hotel",
      "hotel_location": "Mumbai, India",
      "description": "Standard business hotel.",
      "owner_name": "John Doe",
      "email": "owner@grandhotel.com",
      "phone": "+919876543210",
      "media": {
        "logo_image": "https://tvapp.digiemperor.com/uploads/hotel_logos/1720092305_logo_a8d7f2a1.png",
        "cover_image": "https://tvapp.digiemperor.com/uploads/hotel_images/1720092305_image_c8f2b1d3.jpg",
        "slider_images": [
          "https://tvapp.digiemperor.com/uploads/hotel_sliders/1720092305_slider_e8f2b1d3.jpg"
        ]
      },
      "active_plan": {
        "plan_name": "Premium Plan",
        "plan_price": "4999.00",
        "purchase_date": "2026-07-02T10:00:00+00:00",
        "expiry_date": "2026-08-01T10:00:00+00:00"
      }
    }
  }
}
```

### **B. Error - Unauthenticated (401 Unauthorized)**
Returned when the token is missing or invalid.

```json
{
  "status": false,
  "message": "Unauthenticated. Invalid token."
}
```

---

## 4. Flutter Integration Instructions
1. **API Polling**: Periodically (e.g., on app launch or daily) send a `GET` request to `{{BASE_URL}}/api/tv/template/check-version` with the stored `Authorization: Bearer {token}` header.
2. **Version Checking**: Compare the returned `latest_version` string against the local cached template version (e.g., stored using `shared_preferences`).
3. **Download Update**: If the server's `latest_version` is greater than the local version, download the `.zip` file from `download_url` using a background downloader.
4. **Unpack & Apply**: Unzip the downloaded folder and overwrite the local offline web assets directory. Update the locally stored version number.
