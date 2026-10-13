# 📺 Smart TV Demo & Free Trial System — Frontend & API Integration Guide

Yeh document Smart TV (Android TV / Tizen / WebOS / Fire TV) ke Frontend Developer aur Backend Integrator ke liye complete reference guide hai. Isme demo login flow, request/response structures, expiry handling, aur demo-to-paid license conversion ka step-by-step documentation diya gaya hai.

---

## 📌 1. System Overview & Architecture

Hamare Smart TV ecosystem me do tareeqe se TV login hoti hai:

```
                                  ┌───────────────────────────┐
                                  │      TV Login Screen      │
                                  └─────────────┬─────────────┘
                                                │
                      ┌─────────────────────────┴─────────────────────────┐
                      │                                                   │
           [ Paid Hotel Client ]                               [ Prospective Hotel Client ]
           "Enter License Key"                                 "⚡ Try Free 7-Day Demo"
                      │                                                   │
                      ▼                                                   ▼
           POST /api/tv/login                                  POST /api/tv/demo-login
           (license_key, deviceId, etc.)                       (deviceId, macAddress, etc.)
                      │                                                   │
                      ▼                                                   ▼
            Permanent Hotel Access                             7-Day Isolated Trial
                                                               (DigiLounge Showcase Hotel)
```

### ✨ Key Features for TV Frontend:
1. **Zero-Friction 1-Click Login:** TV par remote se lambi license key ya password type karne ki zaroorat nahi hai. Single click se instant live hotel experience milta hai.
2. **Hardware-Locked Anti-Abuse:** Ek hardware device (`deviceId` + `macAddress`) poori life me **sirf 1 baar** demo use kar sakti hai. Clear data / reinstall karne par bhi dobara demo activate nahi hoga.
3. **Isolated Device Expiry:** Har TV ka apna 7-day timer hota hai. Agar Room 101 ki TV expire hoti hai, toh baaki TVs par koi asar nahi hota.
4. **Distributor Commission Linkage:** Agar demo kisi Distributor ne lagwaya hai, toh optional `distributor_code` bheja ja sakta hai. Baad me jab hotel paid license lega, toh usi distributor ko commission milega.
5. **Seamless Paid Conversion:** Jab hotel plan khareedta hai, TV par bas standard "Activate License" me jaakar paid license key enter karni hoti hai. TV bina reset hue Paid Hotel me convert ho jati hai.

---

## 🔌 2. API Endpoints Reference

Base URL (Local): `http://127.0.0.1:8001/api`  
Base URL (Production): `https://your-domain.com/api`

---

### Endpoint 1: TV Demo Registration & Login

Jab user TV screen par **"⚡ Try Free 7-Day Demo"** button dabata hai.

- **Method:** `POST`
- **URL:** `/tv/demo-login`
- **Headers:**
  ```http
  Content-Type: application/json
  Accept: application/json
  ```

#### Request Payload:
```json
{
  "deviceId": "TV-LG-OLED-98234",
  "macAddress": "00:1A:2B:3C:4D:5E",
  "room_no": "DEMO-001",
  "model": "LG C2 OLED 55",
  "brand": "LG",
  "osVersion": "webOS 7.0",
  "ipAddress": "192.168.1.45",
  "distributor_code": "DST-1" 
}
```
*(Note: `distributor_code`, `room_no`, `ipAddress`, `model`, `brand`, `osVersion` optional hain. `deviceId` aur `macAddress` strictly required hain).*

---

#### Success Response (200 OK — Active / New Demo):
```json
{
  "status": true,
  "message": "TV logged in successfully.",
  "data": {
    "auth": {
      "token": "a1b2c3d4e5f6g7h8...80_character_bearer_token...",
      "is_demo": true,
      "demo_expires_at": "2026-10-20T20:15:00.000000Z",
      "demo_days_left": 7
    },
    "template": {
      "template_id": 1,
      "latest_version": "2.0",
      "download_url": "https://your-domain.com/storage/templates/theme1.zip",
      "is_update_available": false
    },
    "device": {
      "room_no": "DEMO-001",
      "device_id": "TV-LG-OLED-98234",
      "mac_address": "00:1A:2B:3C:4D:5E",
      "model": "LG C2 OLED 55",
      "brand": "LG",
      "os_version": "webOS 7.0"
    },
    "hotel": {
      "hotel_name": "DigiLounge Demo Grand Hotel",
      "city": "New Delhi",
      "hotel_location": "Cyber City, Delhi NCR",
      "description": "Official 5-Star Demo Showcase Hotel",
      "owner_name": "Demo Showcase Manager",
      "phone": "+91 9999999999",
      "media": {
        "logo_image": "https://your-domain.com/uploads/hotel_logos/demo_logo.png",
        "cover_image": "https://your-domain.com/uploads/hotel_images/demo_cover.jpg",
        "slider_images": [
          "https://your-domain.com/uploads/hotel_sliders/slide1.jpg",
          "https://your-domain.com/uploads/hotel_sliders/slide2.jpg"
        ]
      }
    },
    "amenities": [
      {
        "sr_no": 1,
        "title": "Infinity Pool & Lounge",
        "description": "Rooftop temperature-controlled swimming pool."
      }
    ],
    "menus": [],
    "room_info": [],
    "our_city": []
  }
}
```

---

#### Error Response (403 Forbidden — Trial Expired):
Jab wahi TV (same `deviceId` ya `macAddress`) apna 7-day trial khatam karne ke baad dobara login karne ki koshish karti hai:

```json
{
  "status": false,
  "error_code": "DEMO_EXPIRED",
  "message": "Demo trial has already expired for this TV device. Please purchase a license."
}
```

---

### Endpoint 2: Protected TV Requests (Heartbeat / Template / Flights)

Har background polling ya feature fetch request par TV ko Bearer Token bhejna hota hai:

- **Method:** `GET`
- **URL:** `/tv/template/check-version` (or `/tv/flights`)
- **Headers:**
  ```http
  Authorization: Bearer <auth.token_from_login>
  Accept: application/json
  ```

#### Middleware Expiry Check:
Jab 7 din poore ho jate hain, backend ka `AuthenticateTvToken` middleware agle hi request par request reject kar deta hai:

**Response (403 Forbidden):**
```json
{
  "status": false,
  "error_code": "DEMO_EXPIRED",
  "message": "Demo trial period has expired. Please purchase a license to continue."
}
```

---

### Endpoint 3: Convert Demo TV to Paid Hotel License

Jab hotel owner demo dekh kar plan purchase kar leta hai aur use real license key milti hai (e.g. `DIST-8921-7711`):

- **Method:** `POST`
- **URL:** `/tv/login`
- **Request Payload:**
  ```json
  {
    "license_key": "DIST-8921-7711",
    "room_no": "Suite 302",
    "deviceId": "TV-LG-OLED-98234",
    "macAddress": "00:1A:2B:3C:4D:5E"
  }
  ```
- **Backend Action:** Backend automatically TV device ko `is_demo = false` aur `demo_status = 'converted'` kar deta hai aur naye paid hotel ke saare real banners, menu, aur room info load kar deta hai!

---

## 🎨 3. TV Frontend Developer Implementation Guide

Frontend TV developer ko app me ye 3 simple handling add karni hain:

### Rule 1: Save Token & Demo Flag in TV Storage
Jab `/api/tv/demo-login` ka response aaye:
```javascript
localStorage.setItem('tv_auth_token', response.data.auth.token);
localStorage.setItem('is_demo_mode', response.data.auth.is_demo);
localStorage.setItem('demo_expires_at', response.data.auth.demo_expires_at);
localStorage.setItem('demo_days_left', response.data.auth.demo_days_left);
```

### Rule 2: Show Demo Badge on Screen (Corner Banner)
Agar `is_demo_mode === true` ho, toh TV UI ke top-right ya bottom-right corner me ek non-intrusive luxury badge render karein:
```html
<div class="demo-badge">
  ⭐ Demo Mode • <span id="daysLeft">7 days left</span>
  <span class="sub-text">Press MENU to Activate License</span>
</div>
```

### Rule 3: Intercept `DEMO_EXPIRED` & Show Fullscreen Lock
Har API call ke Axios / Fetch response interceptor me check karein:
```javascript
axios.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 403) {
      if (error.response.data.error_code === 'DEMO_EXPIRED') {
        // Clear local credentials
        localStorage.removeItem('tv_auth_token');
        
        // Navigate to Fullscreen "Demo Expired" Lock Screen
        showDemoExpiredScreen(error.response.data.message);
      }
    }
    return Promise.reject(error);
  }
);
```

### Rule 4: Demo Expired Lock Screen UI Design
Lock screen par 3 cheezein honi chahiye:
1. **Header:** "Your 7-Day Free Trial Has Ended"
2. **QR Code / Phone:** "Scan QR to contact sales or call +91 9999999999 to activate license"
3. **Button:** `[ Enter Purchased License Key ]` (jo standard license key input box open kare).
