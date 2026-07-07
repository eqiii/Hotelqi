<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Normalize legacy values to canonical ones, then update enum.
        if (config('database.default') === 'mysql') {
            // Step 1: expand enum to include both old and new values so updates won't fail
            DB::statement("ALTER TABLE `payments` MODIFY `payment_status` ENUM('unpaid','pending','paid','failed','expired','refunded','cancelled') NOT NULL DEFAULT 'unpaid'");

            // Step 2: Normalize legacy values to canonical ones
            DB::statement("UPDATE `payments` SET `payment_status` = 'pending' WHERE `payment_status` = 'unpaid'");
            DB::statement("UPDATE `payments` SET `payment_status` = 'paid' WHERE `payment_status` IN ('settlement','capture','success')");
            DB::statement("UPDATE `payments` SET `payment_status` = 'failed' WHERE `payment_status` IN ('failure')");

            // Step 3: restrict enum to canonical set and set default to 'pending'
            DB::statement("ALTER TABLE `payments` MODIFY `payment_status` ENUM('pending','paid','failed','expired','refunded','cancelled') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        if (config('database.default') === 'mysql') {
            DB::statement("ALTER TABLE `payments` MODIFY `payment_status` ENUM('unpaid','paid','failed','expired','refunded') NOT NULL DEFAULT 'unpaid'");
        }
    }
};
