# Restaurant Order Workflow - Implementation Plan

## Analysis Summary

**Root Cause Analysis**: The `RestaurantOrder` is already created during `payment()` with `guest_id`. The `orders()` method queries `$guest->restaurantOrders()` which is a valid HasMany relationship. However, there are several issues:

1. **Missing `order_number` field** - Orders use `invoice_number` but no sequential `order_number` for display
2. **Missing `user_id`** - Direct lookup by user is not possible (only through Guest)
3. **Admin views don't exist** - `admin/restaurant-orders/index.blade.php` and `show.blade.php` are missing
4. **order_status values mismatch** - `pending_payment` used instead of `pending` for initial status
5. **No proper status tracking UI** - User show page doesn't show timeline
6. **MidtransCallbackController uses PaymentStatus enum** which returns string values but the model checks match

## Files to Change

1. `app/Models/RestaurantOrder.php` - Add `order_number`, `user` relationship, fix accessors
2. `app/Http/Controllers/RestaurantController.php` - Fix `payment()` to set proper statuses, generate order_number
3. `app/Http/Controllers/Admin/AdminRestaurantOrderController.php` - Fix validation to include `pending` status
4. `app/Http/Controllers/MidtransCallbackController.php` - Fix restaurant order status update
5. `database/migrations/xxxx_add_order_number_to_restaurant_orders.php` - New migration
6. `resources/views/user/restaurant/orders.blade.php` - Enhanced with order number, status badges
7. `resources/views/user/restaurant/show.blade.php` - Enhanced with status timeline tracking
8. `resources/views/admin/restaurant-orders/index.blade.php` - New admin list view
9. `resources/views/admin/restaurant-orders/show.blade.php` - New admin detail view
10. `resources/views/admin/dashboard.blade.php` - Add restaurant orders link

## Implementation Order

1. ✓ Database migration (add order_number, user_id)
2. ✓ Update RestaurantOrder model
3. ✓ Fix RestaurantController (payment flow, statuses)
4. ✓ Fix MidtransCallbackController  
5. ✓ Fix AdminRestaurantOrderController (validation)
6. ✓ Create admin views (index, show)
7. ✓ Enhance user views (tracking timeline)
8. ✓ Update admin dashboard sidebar
