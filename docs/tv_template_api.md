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
Returned when the bearer token is valid and the latest template details are successfully fetched.

```json
{
  "status": true,
  "message": "Template version details fetched successfully.",
  "latest_version": "2.0",
  "old_version": "1.5",
  "download_url": "https://tvapp.digiemperor.com/uploads/templates/template_v2_0_1720098450.zip",
  "uploaded_at": "2026-07-04T15:09:33+00:00"
}
```

### **B. Error - No Templates Found (404 Not Found)**
Returned if no templates have been uploaded or marked as active by the Super Admin yet.

```json
{
  "status": false,
  "message": "No active templates available at this moment.",
  "latest_version": null,
  "old_version": null,
  "download_url": null,
  "uploaded_at": null
}
```

### **C. Error - Unauthenticated (401 Unauthorized)**
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
