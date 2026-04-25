# Smart LMS — Next.js Homepage Prompt & Implementation Guide

## 🎯 Overview
এই file-এ রয়েছে তোমার Smart LMS এর homepage বানানোর জন্য সম্পূর্ণ Next.js implementation guide।

---

## 📁 File Setup

### 1. Project structure
```
app/
  page.tsx          ← এই file-টি তোমার homepage (আমি দিয়েছি)
  layout.tsx        ← Root layout
components/
  ui/
    Badge.tsx
    StarRating.tsx
```

### 2. `app/layout.tsx`
```tsx
import type { Metadata } from "next";
import "./globals.css";

export const metadata: Metadata = {
  title: "Smart LMS — Smart Learning & Course Growth",
  description:
    "Manage courses, AI assessments, live classes, certificates, and compliance reports from one polished LMS workspace.",
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  );
}
```

### 3. `app/globals.css` (minimal reset)
```css
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}
body {
  font-family: "Inter", "Segoe UI", sans-serif;
  -webkit-font-smoothing: antialiased;
}
```

---

## 🎨 Design System (SRS + Reference থেকে নেওয়া)

| Token | Value |
|---|---|
| Primary dark | `#1A1A2E` |
| Accent gold | `#E8A020` |
| Accent hover | `#CF8F1A` |
| Background light | `#F8F9FB` |
| Text muted | `#6B7280` |
| Border light | `#E5E7EB` |
| Hero gradient | `160deg, #F8F9FB → #EEF2FF` |

---

## 🧩 Sections Breakdown

| Section | SRS Feature |
|---|---|
| Navbar + Sub-tabs | Navigation (AI Studio, Live Classroom, Compliance, Certificates, Billing) |
| Hero | Platform intro + CTA (Dashboard Access, AI Studio, Reset demo) |
| Stats bar | 10,000+ learners, 500+ institutes, 99.5% uptime, 30s AI feedback |
| Better learning ops | Admin Ready + Teacher Tools cards |
| Features grid | Course Builder, AI Assessments, Live Classroom, Compliance, Certificates, Billing |
| Quality banner | CTA banner |
| Workspace chooser | Admin / Teacher / Student workspace tabs |
| Courses catalog | 6 featured courses |
| Testimonials | 4 user reviews |
| Pricing | Starter $49 / Growth $149 / Professional $349 (SRS 5.1 tier matrix) |
| CTA footer banner | Contact + phone |
| Footer | Platform, Learning, AI & Compliance, Access columns |

---

## ⚙️ Dependencies

```bash
# প্রথমে install করো
npm install
# অথবা
yarn install

# Google Fonts (Inter) automatically load হবে CSS import থেকে
# কোনো extra package দরকার নেই এই homepage-এর জন্য
```

---

## 🚀 Routing — Next.js App Router

এই `page.tsx` file টা রাখো:
```
app/page.tsx
```

Next.js automatically `/` route হিসেবে serve করবে।

---

## 🔗 Internal Links (Connect করো)

Homepage-এ এই anchors আছে — পরে real routes দিয়ে replace করো:

| href | Connect করো |
|---|---|
| `#features` | `/features` page |
| `#pricing` | `/pricing` page |
| `#courses` | `/catalog` page |
| `#ai-studio` | `/dashboard/ai-studio` |
| `#live-classroom` | `/dashboard/live-classroom` |
| `#compliance` | `/dashboard/compliance` |
| `#certificates` | `/dashboard/certificates` |
| `#billing` | `/dashboard/billing` |

---

## 📱 Responsive Breakpoints

| Breakpoint | Behavior |
|---|---|
| `< 768px` | Desktop nav hidden, grids go 1-column, footer grid 2-column |
| `768px – 1024px` | 2-column grids |
| `> 1024px` | Full layout |

---

## 🤖 AI Prompt (যদি কোনো AI editor ব্যবহার করো)

```
Build a Next.js 14 App Router homepage for "Smart LMS" — a multi-tenant SaaS 
Learning Management System. 

Design reference: Betopia LMS (dark navy + amber/gold accent, clean enterprise 
feel). Color palette: primary #1A1A2E, accent #E8A020.

Sections required (in order):
1. Sticky navbar with logo "Smart LMS", links: Home, Features, Pricing, Catalog. 
   Right side: Sign in + "Book demo" amber CTA button.
2. Sub-navigation bar with: AI Studio, Live Classroom, Compliance, Certificates, Billing.
3. Hero section: headline "Smart Learning & Course Growth" (first line dark, 
   second line amber), subtext about managing courses/AI assessments/live classes/
   certificates/compliance. 3 CTA buttons: "Dashboard Access" (amber primary), 
   "AI Studio →" (outline), "Reset demo" (ghost). Show stats: active learners, 
   published courses, lessons.
4. Stats bar (dark background): 10,000+ Active Learners, 500+ Institutes, 
   99.5% Uptime SLA, 30s AI Essay Feedback.
5. "Better learning operations" section with utilization card and Admin Ready + 
   Teacher Tools feature cards.
6. Features grid (6 cards): Course Builder, AI Assessments, Live Classroom, 
   Compliance Reports, Certificates, Billing.
7. "Providing High Quality Learning Tools" dark banner with CTA.
8. "Choose Your Dashboard" section with tabbed workspace switcher: 
   Admin / Teacher / Student tabs.
9. Course catalog grid (6 courses) with category badge, thumbnail, module count, hours.
10. Testimonials grid (4 cards) with star ratings.
11. Pricing section: 3 tiers — Starter $49/mo (100 students), Growth $149/mo 
    (500 students, highlighted), Professional $349/mo (2000 students). 
    Features from SRS Section 5.1.
12. CTA banner (dark): "Contact Us to Start Your Learning Journey!" + Contact us button.
13. Footer: logo, brand description, 4 columns: Platform, Learning, AI & Compliance, Access.

Tech: Next.js 14 App Router, TypeScript, no external UI library, 
inline styles only (no Tailwind), "use client" for interactivity.
Mobile responsive. Sticky navbar with scroll shadow effect.
```

---

## ✅ Features Checklist

- [x] Sticky navbar with scroll effect
- [x] Sub-navigation tabs (AI Studio, Live, Compliance, Certs, Billing)
- [x] Hero with animated hover CTAs
- [x] Live Platform card (Assessments + Certificates counter)
- [x] 3 featured course preview cards in hero
- [x] Stats bar (dark background)
- [x] Platform utilization card (82% progress bar)
- [x] Feature grid with hover lift animation
- [x] Workspace tab switcher (Admin / Teacher / Student) — interactive
- [x] Course catalog grid with hover effects
- [x] Testimonials with star ratings + avatar initials
- [x] Pricing (Starter / Growth / Professional) from SRS 5.1
- [x] CTA banner
- [x] Footer with 4 columns + copyright
- [x] Mobile responsive (media queries)
- [x] TypeScript typed
