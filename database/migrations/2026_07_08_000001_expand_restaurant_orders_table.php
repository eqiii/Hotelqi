<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('restaurant_orders', 'invoice_number')) {
                $table->string('invoice_number')->nullable()->after('booking_id');
            }
            if (!Schema::hasColumn('restaurant_orders', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0)->after('invoice_number');
            }
            if (!Schema::hasColumn('restaurant_orders', 'tax')) {
                $table->decimal('tax', 12, 2)->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('restaurant_orders', 'total')) {
                $table->decimal('total', 12, 2)->default(0)->after('tax');
            }
            if (!Schema::hasColumn('restaurant_orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('total');
            }
            if (!Schema::hasColumn('restaurant_orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('payment_method');
            }
            if (!Schema::hasColumn('restaurant_orders', 'order_status')) {
                $table->string('order_status')->default('pending_payment')->after('payment_status');
            }
            if (!Schema::hasColumn('restaurant_orders', 'serve_type')) {
                $table->string('serve_type')->default('now')->after('order_status');
            }
            if (!Schema::hasColumn('restaurant_orders', 'serve_time')) {
                $table->timestamp('serve_time')->nullable()->after('serve_type');
            }
            if (!Schema::hasColumn('restaurant_orders', 'dining_type')) {
                $table->string('dining_type')->default('dine_in')->after('serve_time');
            }
            if (!Schema::hasColumn('restaurant_orders', 'guest_name')) {
                $table->string('guest_name')->nullable()->after('dining_type');
            }
            if (!Schema::hasColumn('restaurant_orders', 'room_number')) {
                $table->string('room_number')->nullable()->after('guest_name');
            }
            if (!Schema::hasColumn('restaurant_orders', 'notes')) {
                $table->text('notes')->nullable()->after('room_number');
            }
            if (!Schema::hasColumn('restaurant_orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('restaurant_orders', 'status')) {
                $table->string('status')->default('pending')->after('paid_at');
            }
        });

        Schema::table('restaurant_order_details', function (Blueprint $table) {
            if (!Schema::hasColumn('restaurant_order_details', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0)->after('price');
            }
        });

        Schema::table('restaurant_menus', function (Blueprint $table) {
            if (!Schema::hasColumn('restaurant_menus', 'stock_quantity')) {
                $table->integer('stock_quantity')->nullable()->after('is_available');
            }
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_orders', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_number',
                'subtotal',
                'tax',
                'total',
                'payment_method',
                'payment_status',
                'order_status',
                'serve_type',
                'serve_time',
                'dining_type',
                'guest_name',
                'room_number',
                'notes',
                'paid_at',
                'status',
            ]);
        });

        Schema::table('restaurant_order_details', function (Blueprint $table) {
            $table->dropColumn('subtotal');
        });

        Schema::table('restaurant_menus', function (Blueprint $table) {
            $table->dropColumn('stock_quantity');
        });
    }
};
