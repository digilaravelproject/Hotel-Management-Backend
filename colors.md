# PAX TV Hospitality - Official UI Color Rulebook (Luxury & Eye-Friendly Edition)

Yeh document **PAX TV Hospitality** ke teeno panels (**Super Admin**, **Distributor**, aur **Hotel Admin**) ke liye official color guidelines set karta hai. 

Website ko neat, clean, modern aur eye-friendly rakhne ke liye hum **Executive Hospitality UI Rule** follow karenge:
- **90% Soft Neutral Colors:** Warm Alabaster (`#F4F6FC`) aur Soft White (`#FFFFFF`) taaki aankhon par glare na pade aur UI ultra-clean dikhe.
- **8% Deep Matte Role Colors:** Left Sidebar ke liye calm, dark, desaturated colors (Har panel ki alag pehchan ke liye).
- **2% Vibrant & Theme Accents:** Active states, CTA buttons, aur key metrics ke liye.

---

## 1. Global Soft Neutrals (Common Across All 3 Panels)
Screen ka 90% hissa inhi soothing colors se banega:

| UI Element | Color Name | HEX Code | Kaha Use Karna Hai (Exact Placement) |
| :--- | :--- | :--- | :--- |
| **Main Background** | Cool Soft Alabaster | `#F4F6FC` | Pure page ke piche ka main background (Content area). |
| **Cards / Containers** | Pure White | `#FFFFFF` | Top Header, Data Cards, Tables, Forms, aur Modals ka background. |
| **Table Header / Hover** | Whisper Slate | `#F8F9FD` | Table ke top header row aur row mouse-hover ke liye (`hover:bg-purple-50/20`). |
| **Borders / Dividers** | Hairline Border | `#E2E8F0` / `#E5E7EB` | Cards ki patli border (`1px solid`) aur table ki dividing lines ke liye. |
| **Primary Text** | Deep Charcoal | `#0F172A` / `#111827` | Main Headings, Table ka data, aur KPI Numbers. |
| **Secondary Text** | Cool Slate | `#64748B` / `#6B7280` | Sub-headings, Form labels, Timestamps, aur Inactive icons. |

> **Logo Placement Rule:** Left Sidebar ke top par **PAX TV LAUNCHER** ka logo clean transparent format me (`h-14 sm:h-16`) lagayein.

---

## 2. Role-Specific Panel Palettes (Sidebar & Key Accents)

### A. Super Admin Panel (Deep Midnight Cosmic Violet & Jobie Vibrant Suite)
*Executive Root Authority, High-Tech Telemetry & Modern Luxury look ke liye.*

- **Left Sidebar Background:** Gradient `from-[#150A42] via-[#0F0631] to-[#090320]` (Deep Midnight Cosmic Violet)
- **Sidebar Inactive Text/Icons:** `text-purple-200/75` (Soft Lilac Mist)
- **Sidebar Active Tab Background:** `#FFFFFF` (Pure White Card with `shadow-lg shadow-black/40` & `rounded-2xl`)
- **Active Tab Text & Icon Accent:** `#150A42` (Deep Cosmic Violet)
- **Primary Button (CTA) Background:** `#150A42` (Deep Cosmic Violet) with hover `#201063` & white text
- **Mobile Floating Bottom Dock:** `#FFFFFF` with active elevated circle badge `#150A42`
- **Dashboard Vibrant Stat Cards (Jobie Aesthetics):**
  - **TV Fleet:** Royal Blue `#2835EB`
  - **Properties Network:** Sky Blue `#1EAAEB`
  - **Licensed Rooms / Active:** Emerald Green `#05C586`
  - **Revenue / Run-Rate:** Lime Green `#85D638`
  - **Pending Reviews / Alerts:** Amber `#F59E0B`
- **Soft Tints (Icon Boxes & Status Pills):**
  - Purple Tint: `#F3E8FF` / `bg-purple-50` with `text-[#150A42]`
  - Emerald Tint: `#ECFDF5` / `bg-emerald-50` with `text-emerald-700`
  - Amber Tint: `#FFFBEB` / `bg-amber-50` with `text-amber-700`
  - Sky Tint: `#F0F9FF` / `bg-sky-50` with `text-sky-700`

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

## 3. Component-Wise Placement Rules

### 1. Left Sidebar (Navigation Menu)
- **Super Admin:** Deep Midnight Gradient (`from-[#150A42] via-[#0F0631] to-[#090320]`). Active link: White rounded card with `#150A42` text and icon.
- **Distributor / Hotel Admin:** Respective dark colors (`#1E293B` / `#2A1820`) with champagne gold active indicators.

### 2. Top Header Bar
- **Background:** `#FFFFFF` (Pure White) with bottom border `1px solid #E2E8F0`.
- **Profile Avatar:** Deep Violet ring (`#150A42`) with high-contrast text.

### 3. Primary CTA Buttons (e.g., "+ Add New Hotel Vendor", "Save Changes")
- **Super Admin:** `bg-[#150A42] hover:bg-[#201063] text-white rounded-2xl shadow-lg shadow-[#150A42]/20`
- **Distributor / Hotel Admin:** Panel Primary or Warm Bronze (`#A68A64`).

### 4. Tables & Data Lists
- **Table Header (Top Row):** Background `#F8F9FD` (Whisper Slate), Text `#64748B` (Semi-Bold Uppercase, `11px` font).
- **Table Body Rows:** Background `#FFFFFF` (White), Text `#0F172A`, Bottom Border `1px solid #F1F5F9`.
- **Row Hover:** `hover:bg-purple-50/25` for Super Admin, giving subtle luxury feedback.

---

## 4. Subdued System Status & Alert Colors (Universal Across All Panels)
| Status | Text / Dot Color | Badge Background | Badge Border (`1px`) | Usage |
| :--- | :--- | :--- | :--- | :--- |
| **Active / Online / Paid** | `#047857` (Forest Green) | `#ECFDF5` (Soft Mint) | `#A7F3D0` | TV Online, Room Active, Payment Success |
| **Pending / Expiring** | `#B45309` (Warm Amber) | `#FFFBEB` (Soft Cream) | `#FDE68A` | Subscription Expiring, Pending Review |
| **Offline / Error / Delete** | `#BE123C` (Rose Crimson) | `#FFF1F2` (Soft Blush) | `#FECDD3` | TV Disconnected, Failed Payment, Delete Button |

---

## 5. Strict "Do Not" Rules for Developers
1. **No Outdated Gold in Super Admin:** Super Admin me old bronze/gold buttons ki jagah `#150A42` Deep Cosmic Violet CTA use karein.
2. **Typography Consistency:** Strict `[Space Mono + Plus Jakarta Sans]` typography throughout all components.
3. **Keep Mobile First:** Mobile grids must default to `grid-cols-2` for quick stats and maintain clearance for the floating bottom navigation bar.
