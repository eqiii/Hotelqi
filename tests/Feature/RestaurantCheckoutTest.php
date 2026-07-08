<?php

namespace Tests\Feature;

use App\Models\Guest;
use App\Models\RestaurantMenu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestaurantCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_non_guest_user_is_redirected_from_restaurant_checkout(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user);

        $this->get('/restaurant/checkout')
            ->assertRedirect('/dashboard');
    }

    public function test_guest_can_add_items_to_cart_and_create_order_after_payment_success(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => 'guest']);
        Guest::factory()->create(['user_id' => $user->id]);

        $menu = RestaurantMenu::create([
            'name' => 'Burger Deluxe',
            'description' => 'Tasty burger',
            'price' => 25000,
            'category' => 'Food',
            'is_available' => true,
            'stock_quantity' => 10,
        ]);

        $this->actingAs($user);

        $this->postJson('/restaurant/cart/add', [
            'menu_id' => $menu->id,
            'quantity' => 2,
        ])->assertOk();

        $this->assertTrue(session()->has('restaurant_cart'));

        session()->put('restaurant_checkout', [
            'cart' => [
                $menu->id => [
                    'menu_id' => $menu->id,
                    'quantity' => 2,
                    'price' => $menu->price,
                    'name' => $menu->name,
                ],
            ],
            'details' => [
                'serve_type' => 'now',
                'serve_time' => null,
                'dining_type' => 'dine_in',
                'guest_name' => 'Test Guest',
                'room_number' => null,
                'notes' => 'Please hurry',
                'payment_method' => 'midtrans',
            ],
        ]);

        $this->get('/restaurant/payment/finish?order_id=restaurant-test&transaction_status=settlement')
            ->assertRedirect('/user/restaurant/orders');

        $this->assertDatabaseHas('restaurant_orders', [
            'guest_id' => $user->guest->id,
            'payment_status' => 'paid',
            'order_status' => 'confirmed',
        ]);
    }
}
