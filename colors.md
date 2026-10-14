# PAX TV Hospitality - UI Color Rulebook

Yeh document **PAX TV Hospitality** ke teeno panels (**Super Admin**, **Distributor**, aur **Hotel Admin**) ke liye official color guidelines set karta hai[cite: 1, 2]. 

Website ko neat, clean aur professional rakhne ke liye hum **80-20 Rule** follow karenge:
* **80% Neutral Colors:** White aur Light Gray (Har panel me same rahega taaki UI clean dikhe)[cite: 2].
* **20% Brand & Role Colors:** Sidebar aur Gold Accents (Har panel ki alag pehchan ke liye)[cite: 2].

---

## 1. Global Neutral Colors (Common Across All 3 Panels)
Yeh colors teeno dashboards me exact same rahenge. Screen ka 80% hissa inhi colors se banega:

| UI Element | Color Name | HEX Code | Kaha Use Karna Hai (Exact Placement) |
| :--- | :--- | :--- | :--- |
| **Main Background** | Light UI Gray | `#F8FAFC` | Pure page ke piche ka main background (Content area ke niche)[cite: 2]. |
| **Cards / Containers** | Pure White | `#FFFFFF` | Top Header, Data Cards, Tables, Forms, aur Modals ka background. |
| **Borders / Dividers** | Soft Gray | `#E2E8F0` | Cards ki patli border aur table ki lines ke liye. |
| **Primary Text** | Dark Slate | `#1E293B` | Main Headings, Table ka data, aur Numbers (jaise Total Revenue). |
| **Secondary Text** | Muted Gray | `#64748B` | Sub-headings, Form labels, Timestamps, aur Inactive icons. |

> **Logo Placement Rule:** Top-left corner (Header ya Sidebar ke upar) me **PAX TV Hospitality** ka logo hamesha **Pure White (`#FFFFFF`)** background box ya transparent PNG format me lagayein[cite: 1].

---

## 2. Role-Specific Panel Palettes (Sidebar & Gold Accents)
Har role ko alag dikhane ke liye sirf **Left Sidebar** aur **Primary Action Buttons (Gold)** ka color change hoga[cite: 2]:

### A. Super Admin Panel (Executive Navy & Royal Gold)
*Logo ke "TV" text (Dark Navy) se inspired[cite: 1, 2].*

* **Left Sidebar Background:** `#0F2537` (Executive Navy)
* **Sidebar Inactive Text/Icons:** `#CBD5E1` (Light Silver)
* **Sidebar Active Tab Background:** `#1B365D` (Midnight Blue)[cite: 2]
* **Gold Accent (Buttons & Highlights):** `#D4AF37` (Royal Gold)
* **Gold Hover State:** `#B5952F` (Darker Gold)
* **Soft Gold Tint (Row Hover / Badges):** `#FBF8F1` (Pale Champagne)

### B. Distributor Panel (Corporate Blue & Warm Gold)
*Logo ke Blue ribbon se inspired[cite: 1, 2].*

* **Left Sidebar Background:** `#004B87` (Corporate Sapphire Blue)
* **Sidebar Inactive Text/Icons:** `#E0F2FE` (Soft Ice Blue)
* **Sidebar Active Tab Background:** `#0056B3` (Bright Royal Blue)[cite: 2]
* **Gold Accent (Buttons & Highlights):** `#E5A93C` (Warm Amber Gold)
* **Gold Hover State:** `#C88E26` (Deep Amber)
* **Soft Gold Tint (Row Hover / Badges):** `#FEF9EE` (Cream Gold)

### C. Hotel Admin Panel (Hospitality Burgundy & Champagne Gold)
*Logo ke "PAX" text (Burgundy) se inspired[cite: 1, 2].*

* **Left Sidebar Background:** `#6E1841` (Imperial Burgundy)
* **Sidebar Inactive Text/Icons:** `#FCE7F3` (Soft Pinkish White)
* **Sidebar Active Tab Background:** `#8A1C56` (Rich Berry)[cite: 2]
* **Gold Accent (Buttons & Highlights):** `#C5A059` (Champagne Gold)
* **Gold Hover State:** `#A88440` (Antique Gold)
* **Soft Gold Tint (Row Hover / Badges):** `#F9F5EB` (Warm Silk)

---

## 3. Component-Wise Placement Rules (Konsa Color Kaha Lagana Hai)

### 1. Left Sidebar (Navigation Menu)
* **Background:** Panel ka Primary Color (`#0F2537` / `#004B87` / `#6E1841`).
* **Normal Menu Links:** White/Light text.
* **Active Menu Link (Jo page khula hai):** 
  * Text aur Icon ka color **Gold** ho jayega.
  * Menu item ke bilkul left edge par `4px solid Gold` ki vertical line (border) aayegi.

### 2. Top Header Bar
* **Background:** `#FFFFFF` (Pure White) with a bottom border of `#E2E8F0`.
* **Profile Badge / Role Tag:** Right side par user ke naam ke paas ek chota badge lagayein jiska background **Soft Gold Tint** ho aur border/text **Gold** ho (e.g., `[Super Admin]`).

### 3. Buttons
* **Primary CTA Button (e.g., "+ Add Hotel", "Save", "Assign Stock"):**
  * Background: **Gold Accent** (`#D4AF37` / `#E5A93C` / `#C5A059`)
  * Text: `#FFFFFF` (White) ya `#0F2537` (Dark Navy - bold font me).
* **Secondary / Cancel Button (e.g., "Cancel", "Export", "Filter"):**
  * Background: `#FFFFFF` (White)
  * Border: `#CBD5E1`
  * Text: `#1E293B` (Dark Slate)

### 4. Dashboard KPI Cards (Top Summary Boxes)
* **Card Background:** `#FFFFFF` (Pure White).
* **Card Numbers (e.g., "1,250 Active TVs"):** `#1E293B` (Dark Slate).
* **Card Icon Box:** Icon ko ek gol ya square box me rakhein jiska background **Soft Gold Tint** (`#FBF8F1`) ho aur icon ka color **Gold** ho.

### 5. Tables & Data Lists
* **Table Header (Top Row):** Background `#F8FAFC` (Light Gray)[cite: 2], Text `#64748B` (Bold Uppercase).
* **Table Body Rows:** Background `#FFFFFF` (White), Text `#1E293B`.
* **Row Hover:** Jab mouse kisi row par jaye, toh background halka sa `#F8FAFC` ho jaye[cite: 2].

---

## 4. System Status & Alert Colors (Same for All Panels)
Logo ke Green, Orange, aur Red ribbons ko sirf status badges aur alerts ke liye use karein[cite: 1, 2]:

| Status | Text / Dot Color | Badge Background | Usage |
| :--- | :--- | :--- | :--- |
| **Active / Online / Paid** | `#16A34A` (Green) | `#DCFCE7` (Light Green) | TV Online, Room Active, Payment Success |
| **Pending / Expiring** | `#EA580C` (Orange) | `#FFEDD5` (Light Orange) | Subscription Expiring, Stock Low |
| **Offline / Error / Delete** | `#DC2626` (Red) | `#FEE2E2` (Light Red) | TV Disconnected, Failed Payment, Delete Button |

---

## 5. Strict "Do Not" Rules for Developers
1. **No Gold Backgrounds:** Gold color ko kabhi bhi bade boxes, sidebars, ya page background me mat lagana. Gold sirf buttons, icons, aur active borders ke liye hai.
2. **No Dark Cards on Light Mode:** Data cards aur tables hamesha `#FFFFFF` (Pure White) rahenge.
3. **Maximum 3 Colors Per Screen:** Ek waqt par screen par sirf White/Gray (80%), Sidebar Color (15%), aur Gold (5%) dikhna chahiye.