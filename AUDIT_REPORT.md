# 🏨 HMIS (Hotel Management Information System) — Comprehensive Audit Report

**Project:** HotelEqi  
**Auditor:** Senior Full Stack Laravel Developer / Software Architect  
**Date:** July 15, 2026  
**Version:** 1.0

---

## 1. 📊 PROGRESS PROJECT

| Area | Progress |
|------|----------|
| **Overall** | **62%** |
| Backend | 70% |
| Frontend (Blade) | 65% |
| Database | 60% |
| Testing | 15% |
| Security Hardening | 50% |
| Deployment Ready | 40% |

---

## 2. ✅ CHECKLIST FITUR

### PUBLIC
| Fitur | Status | Notes |
|-------|--------|-------|
| Landing Page | ✅ Selesai | Hero, About, Rooms, Facilities, Testimonials, Gallery sections |
| Hotel Profile | ⚠ Sebagian | Model exists, view uses `hotel_name()` helper, but no dedicated profile page |
| Room List | ✅ Selesai | `/rooms` with pagination |
| Room Detail | ✅ Selesai | `/rooms/{roomType}` with facilities |
| Room Search | ⚠ Sebagian | Search bar on landing page but no actual search logic in controller |
| Facilities | ⚠ Sebagian | Hardcoded on landing page, not dynamic from DB |
| Restaurant | ✅ Selesai | Menu listing, cart, checkout, payment |
| Gallery | ⚠ Sebagian | Displayed on landing page, but no admin CRUD |
| Contact | ❌ Belum Ada | No contact page, no contact form |
| FAQ | ✅ Selesai | `/faq` route works |

### AUTH
| Fitur | Status | Notes |
|-------|--------|-------|
| Login | ✅ Selesai | With reCAPTCHA support |
| Register | ✅ Selesai | With email verification trigger |
| Email Verification | ✅ Selesai | Using Laravel built-in |
| Forgot Password | ✅ Selesai | Using Laravel built-in |
| Captcha | ⚠ Sebagian | Only on login, not on register |

### CUSTOMER
| Fitur | Status | Notes |
|-------|--------|-------|
| Dashboard | ✅ Selesai | With recent bookings, totals |
| Booking Room | ✅ Selesai | With date selection, price calculation |
| Booking History | ✅ Selesai | Paginated list |
| Payment Midtrans | ✅ Selesai | Snap token, callback, finish page |
| Invoice | ✅ Selesai | Detail page + PDF download |
| Profile | ✅ Selesai | Edit profile |
| Restaurant Order | ✅ Selesai | Cart, checkout, payment, history |

### ADMIN
| Fitur | Status | Notes |
|-------|--------|-------|
| Dashboard | ✅ Selesai | Stats, latest bookings |
| CRUD Room Type | ✅ Selesai | Resource controller |
| CRUD Room | ✅ Selesai | Resource controller |
| CRUD Facility | ❌ Belum Ada | No admin controller for facilities |
| CRUD Gallery | ❌ Belum Ada | No admin controller for gallery |
| CRUD Restaurant Menu | ✅ Selesai | Resource controller |
| CRUD Hotel Profile | ⚠ Sebagian | Only edit/update, no create |
| CRUD Booking | ✅ Selesai | View, confirm, check-in/out, cancel |
| CRUD Customer | ✅ Selesai | View, edit, delete guests |
| CRUD Staff | ❌ Belum Ada | No staff management |
| Manage Payment | ⚠ Sebagian | Via Midtrans callback only, no manual payment management |
| Manage Restaurant Order | ✅ Selesai | View, update status |

### MANAGEMENT
| Fitur | Status | Notes |
|-------|--------|-------|
| Dashboard | ✅ Selesai | Manager dashboard exists |
| Financial Report | ✅ Selesai | With filters, charts |
| Occupancy Report | ❌ Belum Ada | Not implemented |
| Booking Report | ❌ Belum Ada | Not implemented |
| Download PDF | ✅ Selesai | Financial report PDF |

---

## 3. 🚨 ERROR FINDER

### 3.1 Route Errors

| # | File | Issue | Severity |
|---|------|-------|----------|
| E1 | `routes/web.php:21` | CSRF exception in `bootstrap/app.php` for `midtrans/callback` but actual route is `payment/callback` — CSRF will block Midtrans callback | **CRITICAL** |
| E2 | `routes/web.php:101-113` vs `133-144` | Duplicate restaurant cart/checkout routes defined in both `auth` group and `user` guest group — causes route conflicts | **HIGH** |
| E3 | `routes/web.php` | No route for `facilities`, `gallery`, `contact` pages | **MEDIUM** |
| E4 | `routes/web.php` | Admin has no routes for Facility CRUD, Gallery CRUD | **MEDIUM** |
| E5 | `routes/web.php:189-193` | Manager routes missing occupancy report, booking report | **MEDIUM** |

### 3.2 Controller Errors

| # | File | Issue | Severity |
|---|------|-------|----------|
| E6 | `RestaurantController.php:570` | Controller is 570 lines — violates Single Responsibility Principle | **MEDIUM** |
| E7 | `BookingController.php:48-51` | Only checks 1 available room — if multiple rooms of same type exist, only first is used | **MEDIUM** |
| E8 | `BookingController.php:106` | Order ID `booking-{id}` is predictable — no UUID/unique identifier | **LOW** |
| E9 | `AuthController.php:74-100` | CAPTCHA verification logic is duplicated and could be extracted to a FormRequest | **LOW** |
| E10 | `LandingController.php` | No search/filter logic for rooms — search bar on landing does nothing | **MEDIUM** |

### 3.3 Model Errors

| # | File | Issue | Severity |
|---|------|-------|----------|
| E11 | `User.php:16` | Role enum only `['admin', 'guest']` — missing `manager` role in migration | **CRITICAL** |
| E12 | `RestaurantOrder.php` | Duplicate fields: `total` and `total_price`, `status` and `order_status` | **MEDIUM** |
| E13 | `RoomType.php:52` | `hasManyThrough` for bookings could cause N+1 queries | **LOW** |

### 3.4 View Errors

| # | File | Issue | Severity |
|---|------|-------|----------|
| E14 | `landing/index.blade.php:175-184` | Facilities are hardcoded, not from database | **MEDIUM** |
| E15 | Various views | Potential XSS where `{{ $var }}` is used but some user-generated content may not be properly escaped | **LOW** |

### 3.5 Middleware Errors

| # | File | Issue | Severity |
|---|------|-------|----------|
| E16 | `RoleMiddleware.php:18` | Only checks single role — admin cannot access manager routes and vice versa | **MEDIUM** |
| E17 | `bootstrap/app.php:21-24` | CSRF exception path `midtrans/callback` doesn't match actual route `payment/callback` | **CRITICAL** |

### 3.6 Authentication/Authorization Errors

| # | File | Issue | Severity |
|---|------|-------|----------|
| E18 | `routes/web.php:116` | User routes use `role:guest` middleware — but `manager` role can't access user dashboard | **MEDIUM** |
| E19 | `AuthController.php:110-125` | Login-as logic is fragile — could be bypassed | **LOW** |

---

## 4. 🐛 BUG FINDER

| # | Bug | File | Severity |
|---|-----|------|----------|
| B1 | **Midtrans callback blocked by CSRF** — CSRF exception path is `midtrans/callback` but actual route is `payment/callback`. Midtrans server-to-server callback will be rejected with 419 | `bootstrap/app.php:22` | **CRITICAL** |
| B2 | **Booking double-booking possible** — Race condition: two users booking the same room type simultaneously could both get `availableBetween` to return the same room | `BookingController.php:44-46` | **HIGH** |
| B3 | **Room status not reset on cancel** — `cancel()` only resets room to available if status was `occupied`, but room could be `confirmed` without being `occupied` | `AdminBookingController.php:63` | **MEDIUM** |
| B4 | **Restaurant route duplication** — Cart routes defined twice (lines 101-113 and 133-144) causing potential 404 errors | `routes/web.php` | **MEDIUM** |
| B5 | **Payment finish without auth** — `/payment/finish` route is outside auth middleware but tries to access `Auth::user()` | `BookingController.php:144` | **MEDIUM** |
| B6 | **Invoice number uses `$this->id`** — If booking is deleted (soft delete), ID could be reused | `Booking.php:77` | **LOW** |
| B7 | **Manager role missing from migration** — Users table enum only has `admin` and `guest`, so creating a manager via seeder will fail | `migrations/0001_01_01_000000_create_users_table.php:16` | **CRITICAL** |
| B8 | **Restaurant order `total_price` and `total` are duplicates** — Both store same value, could cause inconsistency | `RestaurantController.php:348-349` | **MEDIUM** |

---

## 5. 🗄️ DATABASE REVIEW

### Migration Files Found:
1. `0001_01_01_000000_create_users_table.php` — Users, password_resets, sessions
2. `2026_07_05_180347_add_full_name_to_guests_table.php` — Add full_name to guests
3. `2026_07_10_000000_add_midtrans_order_id_to_restaurant_orders_table.php`
4. `2026_07_13_000001_add_order_number_to_restaurant_orders_table.php`

### Issues:

| # | Issue | Severity |
|---|-------|----------|
| D1 | **Missing `manager` in role enum** — Only `['admin', 'guest']` defined | **CRITICAL** |
| D2 | **No foreign key constraints** — Most tables lack proper FK constraints (cascade on delete) | **HIGH** |
| D3 | **No soft deletes** — No `deleted_at` on any table | **MEDIUM** |
| D4 | **Missing indexes** — No indexes on `status`, `check_in`, `check_out`, `payment_status` columns | **MEDIUM** |
| D5 | **No unique constraint on `room_number`** — Could have duplicate room numbers | **MEDIUM** |
| D6 | **Missing migrations for:** gallery, facilities, hotel_profiles, testimonials, banners, faqs, dynamic_pricings, booking_histories, room_facilities, restaurant_menus, restaurant_orders, restaurant_order_details, payments | **HIGH** |
| D7 | **Naming convention inconsistency** — Some tables use snake_case, some don't follow Laravel conventions | **LOW** |

### Database Score: **60/100**

---

## 6. 🔒 SECURITY AUDIT

| # | Issue | Severity |
|---|-------|----------|
| S1 | **CSRF bypass not working** — Midtrans callback will fail because exception path is wrong | **CRITICAL** |
| S2 | **No rate limiting on login** — Brute force attack possible | **HIGH** |
| S3 | **No rate limiting on registration** — Bot registration possible | **HIGH** |
| S4 | **No CAPTCHA on registration** — Only on login | **MEDIUM** |
| S5 | **Mass assignment protection** — `$fillable` is used correctly in most models | ✅ OK |
| S6 | **Password hashing** — Using `Hash::make()` and `'hashed'` cast | ✅ OK |
| S7 | **XSS protection** — Blade auto-escapes with `{{ }}`, but some raw echoes may exist | ⚠ Check |
| S8 | **SQL injection** — Using Eloquent ORM, safe from injection | ✅ OK |
| S9 | **File upload validation** — Avatar upload in BookingController has no validation rules | **MEDIUM** |
| S10 | **Session security** — Session regeneration on login/logout | ✅ OK |
| S11 | **RoleMiddleware only checks exact match** — No role hierarchy | **LOW** |

### Security Score: **50/100**

---

## 7. 📝 CODE QUALITY

| # | Issue | File | Severity |
|---|-------|------|----------|
| C1 | **Controller terlalu besar** — `RestaurantController` (570 lines) | `RestaurantController.php` | **HIGH** |
| C2 | **Duplikasi kode** — Cart/checkout logic duplicated in `RestaurantController` and `paymentFinish` | `RestaurantController.php` | **HIGH** |
| C3 | **No Service Layer** — Business logic mixed in controllers | Multiple controllers | **MEDIUM** |
| C4 | **No Repository Pattern** — Direct DB queries in controllers | Multiple controllers | **LOW** |
| C5 | **No FormRequest for login** — Validation inline in controller | `AuthController.php:66-69` | **LOW** |
| C6 | **Helper functions in global namespace** — Could conflict with other packages | `Helper.php` | **LOW** |
| C7 | **SOLID violations** — RestaurantController violates Single Responsibility | `RestaurantController.php` | **HIGH** |
| C8 | **Clean code** — Generally good naming, but some methods are too long | Various | **MEDIUM** |

### Code Quality Score: **55/100**

---

## 8. 🎨 UI REVIEW

| # | Aspect | Rating | Notes |
|---|--------|--------|-------|
| U1 | Responsive | ⚠ Good | Landing page is responsive, admin area needs checking |
| U2 | Konsistensi | ✅ Good | Consistent use of Tailwind, amber/gold theme |
| U3 | Sidebar | ✅ Good | Admin sidebar is well-structured |
| U4 | Navbar | ✅ Good | Clean navigation |
| U5 | Card | ✅ Good | Room cards, dashboard cards look professional |
| U6 | Table | ✅ Good | Data tables are clean |
| U7 | Form | ✅ Good | Forms are well-designed |
| U8 | Button | ✅ Good | Consistent button styling |
| U9 | Warna | ✅ Good | Amber/gold theme fits hotel branding |
| U10 | Font | ✅ Good | Playfair Display for headings |
| U11 | UX | ⚠ Good | Some flows could be smoother (booking flow) |

### UI Score: **75/100**

---

## 9. ⚡ PERFORMANCE REVIEW

| # | Issue | Severity |
|---|-------|----------|
| P1 | **N+1 Query potential** — `RoomType::with('facilities')` is used but `hasManyThrough` for bookings could cause N+1 | **MEDIUM** |
| P2 | **No caching** — Hotel profile, room types, facilities are queried on every request | **HIGH** |
| P3 | **No image optimization** — Images loaded from Unsplash, no lazy loading configuration | **LOW** |
| P4 | **No pagination on some queries** — `Gallery::take(8)` is fine but no pagination for large datasets | **LOW** |
| P5 | **Eager loading mostly good** — Controllers use `with()` for relationships | ✅ OK |
| P6 | **No queue for heavy operations** — PDF generation, email sending are synchronous | **MEDIUM** |

### Performance Score: **55/100**

---

## 10. 🏆 LARAVEL BEST PRACTICE

| # | Practice | Status | Notes |
|---|----------|--------|-------|
| L1 | Use Eloquent ORM | ✅ | Yes |
| L2 | Use Form Requests | ⚠ | Only `RegisterRequest`, `StoreBookingRequest`, `StoreRestaurantCartRequest`, `StoreRestaurantCheckoutRequest` |
| L3 | Use Resource Controllers | ⚠ | Some, not all |
| L4 | Use Service Layer | ❌ | No service layer except `MidtransService` |
| L5 | Use Repository Pattern | ❌ | Not implemented |
| L6 | Use Events/Listeners | ⚠ | Only `Registered` event |
| L7 | Use Notifications | ⚠ | Only email verification |
| L8 | Use Queues | ❌ | Not implemented |
| L9 | Use Policies/Gates | ❌ | Using simple middleware instead |
| L10 | Use Database Migrations | ⚠ | Missing many migrations |
| L11 | Use Seeders | ⚠ | Incomplete |
| L12 | Use Config files | ✅ | Good |
| L13 | Use Env files | ✅ | Good |
| L14 | Use Localization | ❌ | Not implemented |
| L15 | Use Testing | ❌ | Minimal/no tests |

---

## 11. ❌ MISSING FEATURES

| # | Feature | Priority | Notes |
|---|---------|----------|-------|
| M1 | **Admin CRUD Gallery** | HIGH | No gallery management |
| M2 | **Admin CRUD Facility** | HIGH | No facility management |
| M3 | **Admin CRUD Staff** | HIGH | No staff/user management for admin/manager roles |
| M4 | **Contact Page** | MEDIUM | No contact form or page |
| M5 | **Occupancy Report (Manager)** | HIGH | Required feature |
| M6 | **Booking Report (Manager)** | HIGH | Required feature |
| M7 | **Room Search Functionality** | MEDIUM | Search bar exists but no logic |
| M8 | **CAPTCHA on Register** | MEDIUM | Only on login |
| M9 | **Manual Payment Management** | MEDIUM | Admin cannot manually update payment status |
| M10 | **Hotel Profile Page (Public)** | LOW | No dedicated profile page |
| M11 | **Dynamic Facilities on Landing** | LOW | Currently hardcoded |
| M12 | **Testimonials CRUD (Admin)** | LOW | No admin management for testimonials |

---

## 12. 📁 FOLDER STRUCTURE REVIEW

```
hmis-ukk/
├── app/
│   ├── Enums/              ✅ Good — PaymentStatus enum
│   ├── Helpers/            ✅ Good — Helper functions
│   ├── Http/
│   │   ├── Controllers/    ⚠ Some controllers too large
│   │   │   ├── Admin/      ✅ Good organization
│   │   │   └── Manager/    ⚠ Missing controllers
│   │   ├── Middleware/     ✅ RoleMiddleware
│   │   └── Requests/       ⚠ Only 4 Form Requests
│   ├── Models/             ✅ Good — 18 models
│   ├── Providers/          ✅ ViewServiceProvider
│   ├── Services/           ⚠ Only MidtransService
│   └── View/               ⚠ Missing
├── bootstrap/              ✅ Good
├── config/                 ✅ Good
├── database/
│   ├── migrations/         ❌ Missing many migrations
│   └── seeders/            ⚠ Incomplete
├── resources/
│   └── views/              ✅ Well-organized
├── routes/
│   └── web.php             ⚠ Some issues
└── public/                 ✅ Good
```

---

## 13. 🏅 FINAL SCORE

| Area | Score |
|------|-------|
| **Backend** | 65/100 |
| **Frontend** | 70/100 |
| **Database** | 60/100 |
| **Architecture** | 55/100 |
| **Security** | 50/100 |
| **Maintainability** | 55/100 |
| **UI/UX** | 75/100 |
| **Overall** | **62/100** |

---

## 14. 🎯 PRIORITY FIX

### 🔴 CRITICAL (Fix Immediately)
1. **CSRF exception path mismatch** — Change `midtrans/callback` to `payment/callback` in `bootstrap/app.php`
2. **Manager role missing from migration** — Add `manager` to role enum in users table migration
3. **Midtrans callback blocked** — Payment processing will fail in production

### 🟠 HIGH (Fix Soon)
4. **Race condition in booking** — Use database transaction with `lockForUpdate()` when assigning rooms
5. **Duplicate restaurant routes** — Remove duplicate route definitions
6. **No rate limiting on auth** — Add throttle middleware to login/register
7. **Missing Gallery CRUD** — Build admin gallery management
8. **Missing Facility CRUD** — Build admin facility management
9. **Missing Occupancy Report** — Build for manager role
10. **Missing Booking Report** — Build for manager role

### 🟡 MEDIUM (Fix When Possible)
11. **RestaurantController too large** — Extract to service layer
12. **No caching** — Add cache for hotel profile, room types
13. **No CAPTCHA on register** — Add reCAPTCHA to registration
14. **Room search not functional** — Implement search logic
15. **Contact page missing** — Build contact page
16. **Missing foreign key constraints** — Add to migrations
17. **Missing indexes** — Add indexes on frequently queried columns

### 🟢 LOW (Nice to Have)
18. **Soft deletes** — Add to relevant models
19. **Queue for PDF/email** — Offload heavy operations
20. **Tests** — Add PHPUnit tests
21. **Localization** — Add language files
22. **Image optimization** — Add lazy loading, thumbnails

---

## 15. 🗺️ ROADMAP

### Phase 1 — Critical Fixes (1-2 days)
- [ ] Fix CSRF exception path for Midtrans callback
- [ ] Fix manager role in migration
- [ ] Add database transaction + lockForUpdate to booking
- [ ] Remove duplicate restaurant routes

### Phase 2 — Core Features (3-5 days)
- [ ] Build Admin Gallery CRUD
- [ ] Build Admin Facility CRUD
- [ ] Build Occupancy Report (Manager)
- [ ] Build Booking Report (Manager)
- [ ] Add rate limiting to auth routes

### Phase 3 — Enhancements (3-5 days)
- [ ] Refactor RestaurantController — extract service layer
- [ ] Add caching for hotel profile, room types
- [ ] Add CAPTCHA to registration
- [ ] Implement room search functionality
- [ ] Build contact page

### Phase 4 — Hardening (2-3 days)
- [ ] Add foreign key constraints to migrations
- [ ] Add database indexes
- [ ] Add soft deletes
- [ ] Add file upload validation
- [ ] Security audit pass

### Phase 5 — Polish (2-3 days)
- [ ] Add PHPUnit tests
- [ ] Add queue for PDF/email
- [ ] Image optimization
- [ ] UI polish and consistency check
- [ ] Performance optimization

### Phase 6 — Deployment (1-2 days)
- [ ] Environment configuration
- [ ] Database migration testing
- [ ] Midtrans production configuration
- [ ] SMTP configuration
- [ ] Final QA pass

**Total Estimated Time: 12-20 days**

---

## 📋 SUMMARY OF FILES NEEDING FIXES

| File | Issue | Priority |
|------|-------|----------|
| `bootstrap/app.php` | CSRF exception path mismatch | 🔴 CRITICAL |
| `database/migrations/0001_01_01_000000_create_users_table.php` | Missing `manager` role | 🔴 CRITICAL |
| `routes/web.php` | Duplicate restaurant routes, missing routes | 🟠 HIGH |
| `app/Http/Controllers/BookingController.php` | Race condition, predictable order ID | 🟠 HIGH |
| `app/Http/Controllers/RestaurantController.php` | Too large, code duplication | 🟡 MEDIUM |
| `app/Http/Controllers/AuthController.php` | No rate limiting, no register CAPTCHA | 🟡 MEDIUM |
| `app/Http/Controllers/LandingController.php` | No search logic | 🟡 MEDIUM |
| `app/Http/Middleware/RoleMiddleware.php` | Single role check only | 🟡 MEDIUM |
| `app/Models/RestaurantOrder.php` | Duplicate fields | 🟡 MEDIUM |
| `resources/views/landing/index.blade.php` | Hardcoded facilities | 🟢 LOW |
| `database/seeders/DatabaseSeeder.php` | Incomplete seeders | 🟡 MEDIUM |

---

*End of Audit Report*
