<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambahkan 'manager' ke role users.
     * Pada SQLite, perubahan enum tidak didukung sehingga migration ini menjadi no-op.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'guest', 'manager') NOT NULL DEFAULT 'guest'");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::table('users')->where('role', 'manager')->delete();
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'guest') NOT NULL DEFAULT 'guest'");
    }
};
