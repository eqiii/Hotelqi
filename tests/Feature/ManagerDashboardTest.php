<?php

namespace Tests\Feature;

use App\Models\Guest;
use App\Models\RestaurantOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagerDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_dashboard_renders_with_restaurant_revenue_metrics(): void
    {
        /** @var User $manager */
        $manager = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        /** @var User $guestUser */
        $guestUser = User::factory()->create(['role' => 'guest']);
        /** @var Guest $guest */
        $guest = Guest::factory()->create(['user_id' => $guestUser->id]);

        RestaurantOrder::create([
            'guest_id' => $guest->id,
            'booking_id' => null,
            'invoice_number' => 'RST-TEST-001',
            'subtotal' => 45000,
            'tax' => 4500,
            'total' => 49500,
            'total_price' => 49500,
            'payment_method' => 'midtrans',
            'payment_status' => 'paid',
            'order_status' => 'confirmed',
            'serve_type' => 'now',
            'dining_type' => 'dine_in',
            'guest_name' => 'Test Guest',
            'room_number' => null,
            'notes' => null,
            'paid_at' => now(),
            'status' => 'confirmed',
        ]);

        $this->withoutMiddleware();
        $this->actingAs($manager);

        $this->get('/manager/dashboard')
            ->assertOk();
    }
}
