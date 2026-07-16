<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update existing data: map old category values to new ones
        DB::statement("UPDATE restaurant_menus SET category = 'food' WHERE LOWER(category) IN ('makanan', 'food', 'makan')");
        DB::statement("UPDATE restaurant_menus SET category = 'drink' WHERE LOWER(category) IN ('minuman', 'drink', 'minum')");
        DB::statement("UPDATE restaurant_menus SET category = 'dessert' WHERE LOWER(category) IN ('dessert', 'pencuci mulut')");
        DB::statement("UPDATE restaurant_menus SET category = 'snack' WHERE LOWER(category) IN ('snack', 'camilan', 'snack')");
        // Set any remaining null/other categories to 'food'
        DB::statement("UPDATE restaurant_menus SET category = 'food' WHERE category IS NULL OR category NOT IN ('food', 'drink', 'dessert', 'snack')");

        Schema::table('restaurant_menus', function (Blueprint $table) {
            $table->string('category', 20)->default('food')->change();
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_menus', function (Blueprint $table) {
            $table->string('category', 100)->nullable()->change();
        });
    }
};
