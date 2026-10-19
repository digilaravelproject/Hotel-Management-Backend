# PAX TV Hospitality - Official UI Color Rulebook (Luxury & Eye-Friendly Edition)

Yeh document **PAX TV Hospitality** ke teeno panels (**Super Admin**, **Distributor**, aur **Hotel Admin**) ke liye official color guidelines set karta hai. 

Website ko neat, clean, modern aur eye-friendly rakhne ke liye hum **90-8-2 Luxury UI Rule** follow karenge:
- **90% Soft Neutral Colors:** Warm Alabaster aur Soft White (Har panel me exact same rahega taaki aankhon par glare na pade aur UI ultra-clean dikhe).
- **8% Deep Matte Role Colors:** Left Sidebar ke liye calm, dark, desaturated colors (Har panel ki alag pehchan ke liye, bina aankhon me chubhe).
- **2% Muted Champagne & Bronze Accents:** Sirf active states aur main buttons ke liye (Chamakne wale yellow gold ki jagah subtle 5-star hospitality bronze).

---

## 1. Global Soft Neutrals (Common Across All 3 Panels)
Yeh colors teeno dashboards me exact same rahenge. Screen ka 90% hissa inhi soothing colors se banega:

| UI Element | Color Name | HEX Code | Kaha Use Karna Hai (Exact Placement) |
| :--- | :--- | :--- | :--- |
| **Main Background** | Warm Alabaster | `#F4F5F7` | Pure page ke piche ka main background (Content area ke niche). |
| **Cards / Containers** | Pure White | `#FFFFFF` | Top Header, Data Cards, Tables, Forms, aur Modals ka background. |
| **Table Header / Hover** | Whisper Gray | `#F9FAFB` | Table ke top header row aur row mouse-hover ke liye. |
| **Borders / Dividers** | Hairline Gray | `#E5E7EB` | Cards ki patli border (`1px solid`) aur table ki dividing lines ke liye. |
| **Primary Text** | Deep Charcoal | `#111827` | Main Headings, Table ka data, aur KPI Numbers (jaise Total Revenue). |
| **Secondary Text** | Warm Slate | `#6B7280` | Sub-headings, Form labels, Timestamps, aur Inactive icons. |

> **Logo Placement Rule:** Left Sidebar ke top par **PAX TV Hospitality** ka logo clean transparent PNG format me lagayein. Logo ke piche alag se koi bhari box na banayein.

---

## 2. Role-Specific Panel Palettes (Sidebar & Subtle Accents)
Har role ko alag dikhane ke liye sirf **Left Sidebar** ka color change hoga. Aankhon ko chubhne wale vibrant colors ki jagah deep, calm matte shades use kiye gaye hain:

### A. Super Admin Panel (Obsidian Slate & Warm Bronze)
*Executive, calm aur authoritative look ke liye.*

- **Left Sidebar Background:** `#111827` (Obsidian Slate)
- **Sidebar Inactive Text/Icons:** `#9CA3AF` (Muted Silver)
- **Sidebar Active Tab Background:** `#1F2937` (Soft Charcoal)
- **Active Tab Border / Accent:** `#C5A880` (Muted Champagne Gold)
- **Primary Button (CTA) Background:** `#A68A64` (Warm Bronze) *ya* `#111827` (Obsidian Slate)
- **Primary Button Hover State:** `#8C7250` (Deep Bronze) *ya* `#374151` (Dark Slate)
- **Soft Tint (Icon Boxes / Role Badge):** `#F5F2EB` (Warm Sand)

### B. Distributor Panel (Nordic Slate Blue & Warm Bronze)
*Corporate aur grounded look ke liye.*

- **Left Sidebar Background:** `#1E293B` (Nordic Slate Blue)
- **Sidebar Inactive Text/Icons:** `#94A3B8` (Cool Muted Gray)
- **Sidebar Active Tab Background:** `#334155` (Muted Steel Blue)
- **Active Tab Border / Accent:** `#C5A880` (Muted Champagne Gold)
- **Primary Button (CTA) Background:** `#27374D` (Deep Slate Blue) *ya* `#A68A64` (Warm Bronze)
- **Primary Button Hover State:** `#1E293B` (Darker Slate) *ya* `#8C7250` (Deep Bronze)
- **Soft Tint (Icon Boxes / Role Badge):** `#F1F5F9` (Soft Mist)

### C. Hotel Admin Panel (Deep Espresso Plum & Warm Bronze)
*5-Star Luxury Hospitality look ke liye.*

- **Left Sidebar Background:** `#2A1820` (Deep Espresso Plum)
- **Sidebar Inactive Text/Icons:** `#B8A6AE` (Muted Dusty Rose-Gray)
- **Sidebar Active Tab Background:** `#3D242F` (Muted Mulberry)
- **Active Tab Border / Accent:** `#C5A880` (Muted Champagne Gold)
- **Primary Button (CTA) Background:** `#3D242F` (Muted Mulberry) *ya* `#A68A64` (Warm Bronze)
- **Primary Button Hover State:** `#2A1820` (Deep Espresso) *ya* `#8C7250` (Deep Bronze)
- **Soft Tint (Icon Boxes / Role Badge):** `#F7F3F5` (Soft Silk)

---

## 3. Component-Wise Placement Rules (Konsa Color Kaha Lagana Hai)

### 1. Left Sidebar (Navigation Menu)
- **Background:** Panel ka Primary Dark Color (`#111827` / `#1E293B` / `#2A1820`).
- **Normal (Inactive) Menu Links:** Muted Gray text (`#9CA3AF` / `#94A3B8` / `#B8A6AE`). Pure white mat rakhein taaki aankhon par zor na pade.
- **Active Menu Link (Jo page khula hai):**
  * Background: Active Tab Background (`#1F2937` / `#334155` / `#3D242F`) with `6px` border-radius.
  * Text aur Icon ka color: `#FFFFFF` (Pure White - Medium Bold).
  * Left Indicator: Menu item ke left edge par `3px solid #C5A880` (Muted Champagne Gold) ki line aayegi.

### 2. Top Header Bar
- **Background:** `#FFFFFF` (Pure White) with a bottom border of `1px solid #E5E7EB`.
- **Profile Badge / Role Tag:** Right side par user ke naam ke paas ek sleek badge lagayein jiska background **Soft Tint** (`#F5F2EB`) ho, border `1px solid #E5E7EB` ho, aur text `#111827` ho (e.g., `Super Admin`).

### 3. Buttons
- **Primary CTA Button (e.g., "+ Add Hotel", "Save", "Assign Stock"):**
  * Background: `#A68A64` (Warm Bronze) ya Panel ka Dark Color (`#111827` / `#27374D` / `#3D242F`).
  * Text: `#FFFFFF` (Pure White - Semi-Bold).
- **Secondary / Cancel Button (e.g., "Cancel", "Export", "Filter"):**
  * Background: `#FFFFFF` (Pure White)
  * Border: `1px solid #E5E7EB`
  * Text: `#374151` (Dark Gray)
  * Hover State: `#F9FAFB` (Whisper Gray)

### 4. Dashboard KPI Cards (Top Summary Boxes)
- **Card Background:** `#FFFFFF` (Pure White) with `1px solid #E5E7EB` border aur subtle shadow (`0 1px 2px rgba(0,0,0,0.03)`).
- **Card Numbers (e.g., "1,250 Active TVs"):** `#111827` (Deep Charcoal - Bold).
- **Card Title / Label:** `#6B7280` (Warm Slate).
- **Card Icon Box:** Icon ko ek `8px` rounded square box me rakhein jiska background `#F5F2EB` (Warm Sand) ho aur icon ka color `#8C7250` (Deep Bronze) ho.

### 5. Tables & Data Lists
- **Table Header (Top Row):** Background `#F9FAFB` (Whisper Gray), Text `#6B7280` (Semi-Bold Uppercase, `12px` font).
- **Table Body Rows:** Background `#FFFFFF` (White), Text `#111827`, Bottom Border `1px solid #E5E7EB`.
- **Row Hover:** Jab mouse kisi row par jaye, toh background `#F9FAFB` (Whisper Gray) ho jaye. (Table rows me koi gold ya heavy color mat lagayein).

---

## 4. Subdued System Status & Alert Colors (Same for All Panels)
Aankhon ko chubhne wale neon colors ki jagah soft pastel backgrounds aur deep readable text tones use karein:

| Status | Text / Dot Color | Badge Background | Badge Border (`1px`) | Usage |
| :--- | :--- | :--- | :--- | :--- |
| **Active / Online / Paid** | `#15803D` (Forest Green) | `#F0FDF4` (Soft Mint) | `#DCFCE7` | TV Online, Room Active, Payment Success |
| **Pending / Expiring** | `#B45309` (Warm Amber) | `#FFFBEB` (Soft Cream) | `#FEF3C7` | Subscription Expiring, Stock Low |
| **Offline / Error / Delete** | `#B91C1C` (Muted Crimson) | `#FEF2F2` (Soft Blush) | `#FEE2E2` | TV Disconnected, Failed Payment, Delete Button |

---

## 5. Strict "Do Not" Rules for Developers
1. **No Bright Yellow/Gold:** Chamakne wala peela gold (`#D4AF37` ya `#FFD700`) poori website me kahi bhi use mat karna. Sirf Muted Champagne (`#C5A880`) aur Warm Bronze (`#A68A64`) hi use karein.
2. **No Colored Table Hovers:** Table ki rows par hover karne par sirf `#F9FAFB` (Whisper Gray) aana chahiye, koi gold ya blue tint nahi.
3. **No Colored Card Backgrounds:** Sare KPI cards, charts, aur tables hamesha `#FFFFFF` (Pure White) rahenge.
4. **Keep It Minimal:** Ek screen par 90% hissa White/Gray hi dikhna chahiye taaki data padhne me aasaan ho aur UI luxury brand jaisa lage.