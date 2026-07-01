<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled']);
            $table->text('notes')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete(); // Admin atau User
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('booking_histories');
    }
};
