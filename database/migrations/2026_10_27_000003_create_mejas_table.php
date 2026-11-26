<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mejas', function (Blueprint $t) {
            $t->id();
            $t->string('nomor')->unique();
            $t->unsignedTinyInteger('kapasitas');
            $t->unsignedTinyInteger('lantai')->default(1);
            $t->string('area')->nullable(); // Ruang Tamu | Meja Makan | Teras | Galeri
            $t->decimal('pos_x', 5, 2)->nullable(); // posisi di dalam area (persen dari lebar)
            $t->decimal('lebar', 5, 2)->nullable(); // lebar meja di denah (persen dari lebar area)
            $t->decimal('tinggi', 5, 2)->nullable(); // tinggi meja di denah (persen dari tinggi area)
            $t->decimal('pos_y', 5, 2)->nullable(); // posisi di denah (persen dari tinggi area)
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('mejas'); }
};
