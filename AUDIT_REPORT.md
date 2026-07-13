# AUDIT REPORT: Restaurant Order Flow

## Audit Date: 2026-07-13
## Auditor: Senior Laravel Software Architect

---

# CRITICAL ISSUES

---

## Issue #1

**Severity:** CRITICAL

**Problem:**
`midtrans_order_id` is silently NOT PERSISTED when creating RestaurantOrder during the `payment()` method. The Midtrans callback can never match any restaurant order. `paymentFinish()` cannot find the pre-created order. Both the callback and the finish handler fail to recognize the order.

**Evidence:**
`app/Models/RestaurantOrder.php` lines 14-33 (`$fillable` array):
```php
protected $fillable = [
    'guest_id', 'booking_id', 'invoice_number', 'subtotal', 'tax', 'total',
    'total_price', 'payment_method', 'payment_status', 'order_status',
    'serve_type', 'serve_time', 'dining_type', 'guest_name', 'room_number',
    'notes', 'paid_at', 'status',
    // ❌ 'midtrans_order_id' IS MISSING
];
```

`app/Http/Controllers/RestaurantController.php` line 357 attempts to mass-assign:
```php
RestaurantOrder::create([
    ...
    'midtrans_order_id' => $orderId,  // ← SILENTLY DROPPED by Eloquent
]);
```

Migration `2026_07_10_000000_add_midtrans_order_id_to_restaurant_orders_table.php` adds the column, but the Model's `$fillable` was never updated to include it.

**Root Cause:**
The `midtrans_order_id` column was added via migration but the `$fillable` array in `RestaurantOrder` was NOT updated to include it. Eloquent mass-assignment protection silently discards the value.

**Affected Files:**
- `app/Models/RestaurantOrder.php` (missing fillable field)
- `app/Http/Controllers/RestaurantController.php` (payment() and paymentFinish() both attempt to save/search midtrans_order_id)
- `app/Http/Controllers/MidtransCallbackController.php` (searches by midtrans_order_id but never finds it)

**Why it breaks the flow:**
1. `payment()` creates RestaurantOrder → `midtrans_order_id` is NULL in DB
2. Midtrans sends server-to-server notification → `/payment/callback` searches `WHERE midtrans_order_id = 'restaurant-XXX'` → returns NULL → returns 404 "RestaurantOrder not found" → **payment status NEVER updated via callback**
3. `paymentFinish()` searches by `midtrans_order_id` → cannot find the pre-created order → falls to safety-net code
4. Safety-net creates a SECOND order from session data → still `midtrans_order_id` is NULL again
5. If user refreshes `paymentFinish()` → creates THIRD order (duplicate)
6. Midtrans callback still cannot update any of these orders because `midtrans_order_id` is NULL on all of them
7. History might show some orders (if safety-net succeeded), but payment_status is inconsistently set

**Recommended Fix:**
Add `'midtrans_order_id'` to the `$fillable` array in `RestaurantOrder` model.

---

## Issue #2

**Severity:** CRITICAL

**Problem:**
`paymentFinish()` can create DUPLICATE orders on every invocation because it never finds the pre-created order (due to Issue #1) and has no idempotency guard besides `midtrans_order_id` lookup.

**Evidence:**
`app/Http/Controllers/RestaurantController.php` lines 427-437:
```php
DB::transaction(function () use ($guest, $checkout, $orderId) {
    $order = RestaurantOrder::query()
        ->where('midtrans_order_id', $orderId)  // ← never finds (Issue #1)
        ->where('guest_id', $guest->id)
        ->first();

    if (!$order) {
        // Safety net: creates DUPLICATE order from session
        if (empty($checkout['cart'] ?? []) || empty($checkout['details'] ?? [])) {
            throw new \RuntimeException('Restaurant order not found for midtrans_order_id');
        }
        $order = RestaurantOrder::create([...]);  // ← NEW order created
    }
```

There is NO unique constraint on `midtrans_order_id` and no check against `guest_id + invoice_number` or any other uniqueness.

**Root Cause:**
The safety-net code assumes that if `midtrans_order_id` lookup fails, the order doesn't exist. But due to Issue #1, the lookup ALWAYS fails. Every call to `paymentFinish()` creates a new order if session data exists.

**Affected Files:**
- `app/Http/Controllers/RestaurantController.php` (paymentFinish method)
- `app/Models/RestaurantOrder.php` (no unique constraint)

**Why it breaks the flow:**
- If session data persists, multiple calls to `paymentFinish()` create multiple identical orders
- If session data is lost (second call), RuntimeException is thrown → user sees "Terjadi kesalahan" error
- Inconsistent order state: some orders have payment_status='pending' (from payment()), some have 'paid' (from paymentFinish())
- Manager financial report double-counts or inconsistently counts restaurant income

**Recommended Fix:**
Add a unique constraint on `(guest_id, midtrans_order_id)` and fix Issue #1 first. Then validate existence by guest_id + order status rather than midtrans_order_id alone if midtrans_order_id could be null for non-Midtrans payments.

---

## Issue #3

**Severity:** HIGH

**Problem:**
Route name collision and dual route registration for restaurant routes. The same routes defined with `name('restaurant.*')` under `auth` middleware AND with `name('user.restaurant.*')` under `auth + verified + role:guest` middleware both exist simultaneously, causing potential confusion and the `user.restaurant.payment.finish` route (used in payment view) requires verified email.

**Evidence:**
`routes/web.php` lines 90-113:
```php
Route::middleware(['auth'])->group(function () {
    Route::get('/restaurant/payment/finish', [RestaurantController::class, 'paymentFinish'])->name('restaurant.payment.finish');
    Route::get('/restaurant/orders', [RestaurantController::class, 'orders'])->name('restaurant.orders');
    // ... other restaurant routes
});

Route::middleware(['auth', 'verified', 'role:guest'])->prefix('user')->name('user.')->group(function () {
    // Lines 137-142 REDEFINE the same routes:
    Route::get('/restaurant/payment/finish', [RestaurantController::class, 'paymentFinish'])->name('restaurant.payment.finish');
    Route::get('/restaurant/orders', [RestaurantController::class, 'orders'])->name('restaurant.orders');
    // ...
});
```

Note: line 138 uses `name('restaurant.payment.finish')` but due to `name('user.')` prefix on the group, it becomes `user.restaurant.payment.finish`.

The payment view at `resources/views/restaurant/payment.blade.php` line 31:
```javascript
window.location.href = '{{ route('user.restaurant.payment.finish') }}?order_id=...'
```

This generates URL `/user/restaurant/payment/finish` which requires:
1. Authentication ✓
2. Verified email (might fail if user hasn't verified)
3. Role must be 'guest' (might fail for admin/manager testing)

**Root Cause:**
Routes are registered twice with different middleware stacks but pointing to the same controller methods.

**Affected Files:**
- `routes/web.php`
- `resources/views/restaurant/payment.blade.php`
- All restaurant-related views that generate routes

**Why it breaks the flow:**
- If the user has not verified their email, the redirect after payment fails with a 403 or redirect to login
- The user may see the wrong page after payment, never reaching `paymentFinish()`
- This means the order stays in 'pending' status and history appears empty
- Admin/manager roles testing the flow would be locked out (redirected to dashboard)

**Recommended Fix:**
Remove the duplicate routes from lines 90-113 OR from lines 116-143. Keep only ONE set of routes with the appropriate middleware. Decide: either all restaurant routes require `verified + role:guest` OR just `auth`. If the finish URL needs to work without email verification, keep a separate unverified route for the callback/finish redirect.

---

# HIGH SEVERITY ISSUES

---

## Issue #4

**Severity:** HIGH

**Problem:**
Midtrans server-to-server callback `/payment/callback` cannot update any restaurant order's payment status because it only looks up by `midtrans_order_id`, which is never populated in the database (Issue #1). The callback silently fails with a 404 for ALL restaurant orders.

**Evidence:**
`app/Http/Controllers/MidtransCallbackController.php` lines 37-76:
```php
if (str_starts_with($orderId, 'restaurant-')) {
    // ... determine payment status ...

    $restaurantOrder = \App\Models\RestaurantOrder::query()
        ->where('midtrans_order_id', $orderId)   // NEVER matches (Issue #1)
        ->first();

    if (!$restaurantOrder) {
        Log::warning('Midtrans callback: RestaurantOrder tidak ditemukan', ['order_id' => $orderId]);
        return response('RestaurantOrder not found', 404);  // ← Silent failure
    }
    // Update is NEVER reached
    $restaurantOrder->update([...]);
}
```

**Root Cause:**
The callback handler assumes the order exists with the matching `midtrans_order_id`. Since this field is never filled (Issue #1), the callback always returns 404.

**Affected Files:**
- `app/Http/Controllers/MidtransCallbackController.php`
- Storage logs will show continuous "RestaurantOrder tidak ditemukan" warnings

**Why it breaks the flow:**
- Midtrans considers the callback successful (HTTP 200 expected, but we return 404)
- Midtrans may retry, continuing to fail
- Payment status is NEVER updated via the primary callback channel
- The ONLY path to update payment status is via `paymentFinish()` browser redirect
- If the browser redirect fails (user closes tab, network issue, etc.), the order is stuck in 'pending' forever
- Restaurant income never counted in manager finance if payment_status is not 'paid'
- Finance report inconsistency: booking payments work (via callback), restaurant payments don't

**Recommended Fix:**
Fix Issue #1 first. Consider also adding a fallback lookup by `invoice_number` or `guest_id + created_at` in the callback.

---

## Issue #5

**Severity:** HIGH

**Problem:**
Order is pre-created in `payment()` method BEFORE payment confirmation, but if the `paymentFinish()` safety-net creates a second order, the first pending order becomes an ORPHAN record. There is no cleanup mechanism.

**Evidence:**
`app/Http/Controllers/RestaurantController.php` lines 327-377:
```php
// This runs BEFORE payment, just showing the payment page
DB::transaction(function () use ($guest, $checkout, $orderId) {
    // Creates order with payment_status = 'pending'
    $order = RestaurantOrder::create([...]);
    // Creates order details
    RestaurantOrderDetail::create([...]);
});
```

Then in `paymentFinish()` (lines 427-510):
```php
// When it can't find the pre-created order, it creates ANOTHER one
$order = RestaurantOrder::create([...]);  // payment_status = 'paid'
```

**Root Cause:**
The pre-creation in `payment()` is intended to show the order immediately after payment, but it creates a race condition where two orders exist for one payment.

**Affected Files:**
- `app/Http/Controllers/RestaurantController.php`
- `app/Models/RestaurantOrder.php`

**Why it breaks the flow:**
- Duplicate orders confuse the guest's order history
- If `paymentFinish()` fails, the orphan 'pending' order remains visible but the actual paid order never gets created
- Stock decrement happens in `paymentFinish()` but not in `payment()`, causing inconsistency
- Financial reporting counts only one order (the paid one) but the orphan's items are not decremented from stock

**Recommended Fix:**
Remove the pre-creation from `payment()`. Only create the order when payment is confirmed (in `paymentFinish()` or callback). If you need to show order ID before payment, save to session only.

---

# MEDIUM SEVERITY ISSUES

---

## Issue #6

**Severity:** MEDIUM

**Problem:**
Variable scope/shadowing bug in `payment()` method. `$subtotal` and `$tax` are calculated outside the DB transaction closure (lines 300-302) then recalculated and shadowed INSIDE the closure (lines 342-343). The outer variables are used for the view, the inner ones for the DB. If the transaction recalculates differently, the view shows different amounts than what's stored.

**Evidence:**
`app/Http/Controllers/RestaurantController.php` lines 300-302:
```php
$subtotal = collect($checkout['cart'])->sum(fn($item) => ...);
$tax = round($subtotal * 0.1, 2);
$total = $subtotal + $tax;
```

Lines 342-343 inside closure:
```php
$order = RestaurantOrder::create([
    'subtotal' => $subtotal = $items->sum(fn($i) => ...),  // Shadows outer $subtotal
    'tax' => $tax = round($subtotal * 0.1, 2),              // Uses inner $subtotal
    'total' => $subtotal + $tax,                            // Inner scope
    'total_price' => $subtotal + $tax,
```

**Why it breaks the flow:**
- Minor: if `$checkout['cart']` differs from `$items` (e.g., by the time the transaction executes), the displayed total on payment page could differ from the stored total
- Could cause tax calculation inconsistencies in edge cases

**Recommended Fix:**
Pull the subtotal/tax calculation out of the closure into a shared variable before the transaction, and reuse it both for the view and the DB insertion.

---

## Issue #7

**Severity:** MEDIUM

**Problem:**
Stock decrement in `paymentFinish()` happens twice for the same order: once in the safety-net creation loop (lines 484-486) and once in the post-creation loop (lines 500-509). This double-decrements stock for safety-net creations.

**Evidence:**
`app/Http/Controllers/RestaurantController.php`:
```php
// Lines 484-486: Inside safety-net order creation
if ($menu->stock_quantity !== null) {
    $menu->decrement('stock_quantity', $qty);
}

// Lines 500-509: Outside the safety-net block, runs for ALL cases
$order->loadMissing(['details']);
foreach ($order->details as $detail) {
    $menu = RestaurantMenu::lockForUpdate()->find($detail->restaurant_menu_id);
    if ($menu && $menu->stock_quantity !== null) {
        $menu->decrement('stock_quantity', (int)$detail->quantity);
    }
}
```

**Why it breaks the flow:**
- Stock quantity is decremented TWICE for safety-net orders
- Over time, stock goes negative or runs out prematurely
- Regular flow (order found by midtrans_order_id) only decrements once (the second loop)

**Recommended Fix:**
Remove the stock decrement from the safety-net creation block (lines 484-486). Only decrement in the post-creation loop (lines 500-509), which runs for all cases.

---

## Issue #8

**Severity:** MEDIUM

**Problem:**
The guest name in `dine_in` mode is NOT saved from the checkout form. In `storeCheckout()`, `guest_name` is only set for `dine_in`, but in `payment()`, the `guest_name` is read from `$checkout['details']['guest_name']` which only exists for `dine_in`. For `room_service`, `guest_name` is always null.

**Evidence:**
`app/Http/Controllers/RestaurantController.php` lines 274-277:
```php
'guest_name' => $request->dining_type === 'dine_in' ? $request->guest_name : null,
'room_number' => $request->dining_type === 'room_service' ? $request->room_number : null,
```

This is by design, so not a bug per se.

---

# LOW SEVERITY ISSUES

---

## Issue #9

**Severity:** LOW

**Problem:**
The `payment()` method generates `$orderId = 'restaurant-' . uniqid()` which is not cryptographically secure and could theoretically collide under high concurrency.

**Recommended Fix:**
Use `uniqid('restaurant-', true)` for more entropy, or better: `Str::uuid()`.

---

## Issue #10

**Severity:** LOW

**Problem:**
The `getSubtotalAttribute()` accessor in `RestaurantOrderDetail` (line 42) re-computes subtotal from quantity × price each time it's accessed, ignoring the stored `subtotal` column value. The migration added a `subtotal` column, but the accessor overrides it.

**Evidence:**
`app/Models/RestaurantOrderDetail.php` lines 40-43:
```php
public function getSubtotalAttribute(): float
{
    return $this->quantity * $this->price;
}
```

This means the stored `subtotal` column is never used when reading, only when writing.

**Why it breaks the flow:**
- Not a critical issue since the computed value matches the stored value (ideally)
- If the price or quantity were updated after creation without recalculating subtotal, the stored value would be stale but the accessor would return the correct calculated value
- Minor inconsistency between read and write

---

# ROOT CAUSE RANKING

Ranked from most likely to least likely:

| Rank | Issue # | Root Cause | Confidence |
|------|---------|------------|------------|
| 1 | #1 | `midtrans_order_id` not in `$fillable` | 100% |
| 2 | #3 | Dual route registration / wrong middleware | 85% |
| 3 | #4 | Midtrans callback cannot match restaurant orders | 80% |
| 4 | #2 | No idempotency guard → duplicate orders | 75% |
| 5 | #5 | Pre-creation in payment() creates orphans | 70% |
| 6 | #7 | Double stock decrement in paymentFinish() | 60% |
| 7 | #6 | Variable shadowing in payment() | 40% |
| 8 | #9 | Weak order_id generation | 20% |
| 9 | #10 | Subtotal accessor override | 15% |

---

# SINGLE MOST PROBABLE ROOT CAUSE

**Issue #1: `midtrans_order_id` missing from `RestaurantOrder::$fillable`**

**Confidence: 95%**

## Explanation

This single bug creates a cascade of failures that explains ALL five problem statements:

### Problem 1: "Customer successfully completes Midtrans payment. Payment page says success."
✅ Midtrans payment succeeds. The Snap popup shows success. The `onSuccess` callback fires. Everything looks good to the customer.

### Problem 2 & 3: "Restaurant Order History is EMPTY after payment AND after logout/login"
The `paymentFinish()` redirect handler searches for the order by `midtrans_order_id`:
```php
$order = RestaurantOrder::query()
    ->where('midtrans_order_id', $orderId)  // ← NULL in DB, never matches
    ->where('guest_id', $guest->id)
    ->first();
```
Since the field was never saved, the query returns null. The fallback safety-net code tries to create the order from session data. **If the session data expired or was lost** during the redirect from Midtrans (e.g., session garbage collection, race condition, or the redirect opened a new session context), the safety-net throws a RuntimeException:

```php
if (empty($checkout['cart'] ?? []) || empty($checkout['details'] ?? [])) {
    throw new \RuntimeException('Restaurant order not found for midtrans_order_id');
}
```

This exception is caught, logged, and the user is redirected to the orders page with an error message ("Terjadi kesalahan saat menyimpan pesanan"). **No order was ever created with 'paid' status.** The pre-created order from `payment()` exists with `payment_status = 'pending'`, but it was never updated to 'paid' because:
1. The server callback returned 404 (midtrans_order_id was null)
2. The paymentFinish safety-net failed (session data missing)

The user sees an empty order history because:
- If the pre-created order exists (pending), it might be confused with no order at all if the user refreshes/logs out
- If the safety-net also failed, there truly is NO paid order in the database

**After logout/login:** The pending order from `payment()` still exists but appears as a pending payment, not a completed order. The user might not consider a "pending" order as "history." Additionally, if the Guest → User relationship is session-based (the user logs in and the `$guest` resolves to a different record), the pre-created order wouldn't even be visible.

### Problem 4: "Manager financial report sometimes doesn't include restaurant income"
The ManagerFinanceController (line 73) queries:
```php
$restaurantQuery = RestaurantOrder::where('payment_status', 'paid');
```

If the order was never updated to 'paid' (because the callback failed and paymentFinish failed), it won't appear in the financial report. Even if `paymentFinish()` creates a duplicate order as 'paid', the midtrans_order_id is still null, and the system loses the ability to reconcile payments.

### Problem 5: "The overall restaurant order flow feels inconsistent"
- Some orders might appear as 'pending' (from pre-creation)
- Some orders might appear as 'paid' (from safety-net)
- Some orders might not appear at all (if both failed)
- The callback logs show continuous "RestaurantOrder not found" errors
- Stock is inconsistently decremented
- No reliable way to reconcile which payments went through vs. which orders were created

## Fix Priority

1. **IMMEDIATE:** Add `'midtrans_order_id'` to `RestaurantOrder::$fillable`
2. **IMMEDIATE:** Add unique constraint `(guest_id, midtrans_order_id)` to prevent duplicates
3. **HIGH:** Remove duplicate route definitions, use single middleware stack
4. **HIGH:** Remove pre-creation from `payment()`, only create on confirmation
5. **MEDIUM:** Fix double stock decrement in `paymentFinish()`
6. **MEDIUM:** Add fallback lookup in callback (by invoice_number or guest_id + date)
