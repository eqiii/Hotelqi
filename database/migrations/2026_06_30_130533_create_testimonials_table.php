<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name'); // Jika guest null, bisa diisi manual
            $table->text('message');
            $table->tinyInteger('rating'); // 1 - 5
            $table->string('image')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
