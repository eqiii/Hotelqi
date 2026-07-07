<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambahkan 'manager' ke enum role pada tabel users.
     * Tidak mengubah data atau struktur lain.
     */
    public function up(): void
    {
        // ALTER TABLE langsung — cara paling aman untuk extend ENUM di MySQL
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'guest', 'manager') NOT NULL DEFAULT 'guest'");
    }

    public function down(): void
    {
        // Hapus user manager dulu agar tidak melanggar constraint, lalu revert enum
        DB::table('users')->where('role', 'manager')->delete();
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'guest') NOT NULL DEFAULT 'guest'");
    }
};
