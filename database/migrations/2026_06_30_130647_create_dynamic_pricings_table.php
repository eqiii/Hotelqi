<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dynamic_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Weekend, High Season, dll
            $table->enum('type', ['weekend', 'holiday', 'high_season', 'low_season']);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('price_adjustment', 10, 2); // Bisa nominal tambah/kurang
            $table->decimal('price_multiplier', 5, 2)->default(1.00); // Atau persentase (1.20 = 120%)
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('dynamic_pricings');
    }
};
