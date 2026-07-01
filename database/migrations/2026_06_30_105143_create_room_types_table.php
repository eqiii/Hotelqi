<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Deluxe, Superior, dll
            $table->text('description')->nullable();
            $table->decimal('base_price', 12, 2);
            $table->integer('max_guest')->default(2);
            $table->integer('total_bed')->default(1);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
